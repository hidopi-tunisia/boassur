<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Vinkla\Hashids\Facades\Hashids;

class Commande extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'destination_id',
        'site_id',
        'quote_id',
        'quote_nom',
        'quote_priceline',
        'depart',
        'retour',
        'prix_voyage',
        'montant',
        'reference',
        'email_id',
        'email_statut',
        'email_date',
    ];

    protected $appends = ['hashid', 'prix_voyage_euro', 'creation', 'depart_formate', 'retour_formate'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function voyageur(): HasOne
    {
        return $this->HasOne(Voyageur::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function paiement(): HasOne
    {
        return $this->HasOne(Paiement::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reservation(): HasOne
    {
        return $this->HasOne(Reservation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accompagnants(): HasMany
    {
        return $this->hasMany(Accompagnant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destination(): BelongsTo
    {
        return $this->BelongsTo(Destination::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->BelongsTo(Site::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contrat(): BelongsTo
    {
        return $this->BelongsTo(Contrat::class, 'quote_id', 'quote_id');
    }

    /**
     * Retourne le ID hashé
     *
     * @return string
     */
    public function getHashidAttribute()
    {
        return Hashids::encode($this->id);
    }

    public function getDepartFormateAttribute()
    {
        if ($this->depart) {
            return Carbon::createFromFormat('Y-m-d', $this->depart)->format('d/m/Y');
        }

        return $this->depart;
    }

    public function getRetourFormateAttribute()
    {
        if ($this->retour) {
            return Carbon::createFromFormat('Y-m-d', $this->retour)->format('d/m/Y');
        }

        return $this->retour;
    }

    public function getCreationAttribute()
    {
        if ($this->created_at) {
            return $this->created_at->format('d/m/Y H:i');
        }

        return $this->created_at;
    }

    public function getPrixVoyageEuroAttribute()
    {
        return number_format($this->prix_voyage / 100, 2, ',', '&nbsp;') . '&nbsp;€';
    }

    /**
     * Retourne le SHASIGN pour le paiement sécurisé Ogone
     *
     * @return string
     */
    public function getShasign()
    {
        $hashid = strtoupper($this->hashid);
        $backUrl = $this->site->url . '/acheter-une-assurance?id=' . $hashid;

        $mot = 'AMOUNT=' . $this->montant . env('INGENICO_KEY');
        $mot .= 'BACKURL=' . $backUrl . env('INGENICO_KEY');
        $mot .= 'CANCELURL=' . $backUrl . env('INGENICO_KEY');
        $mot .= 'CN=' . strtoupper($this->voyageur->nom_complet) . env('INGENICO_KEY');
        $mot .= 'CURRENCY=EUR' . env('INGENICO_KEY');
        $mot .= 'EMAIL=' . strtoupper($this->voyageur->email) . env('INGENICO_KEY');
        $mot .= 'HOMEURL=' . $this->site->url . env('INGENICO_KEY');
        $mot .= 'LANGUAGE=fr_FR' . env('INGENICO_KEY');
        $mot .= 'LOGO=logo-presence.png' . env('INGENICO_KEY');
        $mot .= 'ORDERID=' . $hashid . env('INGENICO_KEY');
        $mot .= 'OWNERTELNO=' . $this->voyageur->telephone . env('INGENICO_KEY');
        $mot .= 'OWNERZIP=' . strtoupper($this->voyageur->cp) . env('INGENICO_KEY');
        $mot .= 'PSPID=' . env('INGENICO_PSID') . env('INGENICO_KEY');

        return hash('sha512', $mot);
    }

    /**
     * Retourne le MAC pour le paiement sécurisé Monetico
     *
     * @return string
     */
    public function getMac()
    {
        $prix = number_format($this->montant / 100, 2);

        // Générer le contexte
        $contexte = (object) [
            'billing' => [
                'civility' => $this->voyageur->civilite === 1 ? 'mr' : 'mme',
                'firstName' => $this->voyageur->prenom ?? null,
                'lastName' => $this->voyageur->nom ?? null,
                'addressLine1' => $this->voyageur->adresse ?? null,
                'addressLine2' => $this->voyageur->adresse2 ?? null,
                'city' => $this->voyageur->ville ?? null,
                'postalCode' => $this->voyageur->cp ?? null,
                'country' => $this->voyageur->pays->alpha2,
                'email' => $this->voyageur->email,
            ],
        ];

        $contexteEncode = base64_encode(json_encode($contexte));

        $mac = 'TPE=' . config('services.monetico.etp');
        $mac .= '*contexte_commande=' . $contexteEncode;
        $mac .= '*date=' . $this->created_at->format('d/m/Y:H:i:s');
        $mac .= '*lgue=FR';
        $mac .= '*mail=' . $this->voyageur->email;
        $mac .= '*montant=' . $prix . 'EUR';
        $mac .= '*reference=' . $this->hashid;
        $mac .= '*societe=' . config('services.monetico.company');
        $mac .= '*url_retour_err=' . $this->site->url . '/paiement-refuse';
        $mac .= '*url_retour_ok=' . $this->site->url . '/paiement-accepte';
        $mac .= '*version=3.0';

        $key = self::getUsableKey(config('services.monetico.key'));
        $hash = strtoupper(hash_hmac('sha1', $mac, $key));

        return ['mac' => $hash, 'contexte' => $contexteEncode];
    }

    /**
     * Retourne les paramètres nécessaires à CreateBooking
     *
     * @return array
     */
    public function getCreateBookingParams()
    {
        $ref = intval($this->site_id, 10) === 2 ? $this->voyageur->numero : $this->hashid;

        return [
            'ref' => $ref,
            'voyageur' => $this->voyageur->only('nom', 'prenom', 'date_naissance', 'telephone', 'email'),
            'accompagnants' => $this->accompagnants->map(function ($item) {
                return $item->only('nom', 'prenom', 'date_naissance');
            }),
            'contrat' => [
                'quote_id' => $this->quote_id,
                'quote_priceline' => $this->quote_priceline,
                'commande_id' => $ref,
            ],
            'prix_voyage' => $this->prix_voyage,
            'date_depart' => $this->depart,
            'date_retour' => $this->retour,
            'code_pays_depart' => 'FR',
            'code_pays_destination' => $this->destination->alpha2,
        ];
    }

    /**
     * Renvoie la cle dans un format utilisable par la certification hmac
     * https://github.com/nursit/bank/blob/master/presta/cmcic/inc/cmcic.php
     */
    public static function getUsableKey($key)
    {
        $hexStrKey = substr($key, 0, 38);
        $hexFinal = '' . substr($key, 38, 2) . '00';

        $cca0 = ord($hexFinal);

        if ($cca0 > 70 && $cca0 < 97) {
            $hexStrKey .= chr($cca0 - 23) . substr($hexFinal, 1, 1);
        } else {
            if (substr($hexFinal, 1, 1) == 'M') {
                $hexStrKey .= substr($hexFinal, 0, 1) . '0';
            } else {
                $hexStrKey .= substr($hexFinal, 0, 2);
            }
        }

        return pack('H*', $hexStrKey);
    }
}
