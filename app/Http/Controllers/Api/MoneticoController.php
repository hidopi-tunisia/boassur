<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmationEmailNotDelivered;
use App\Models\Commande;
use App\PresenceWS\PresenceQuery;
use App\Services\SendinBlue\Client as NotificationClient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Str;

class MoneticoController extends Controller
{
    /**
     * Callback confirmation paiement
     *
     * @param  Request  $request
     */
    public function confirm(Request $request)
    {
        Log::info(json_encode($request->all()));
        if ($request->has('MAC') === false) {
            return $this->respondFailure();
        }

        // S'assurer que la requête vient de Monetico en validant le Hashmac
        $contexte = $request->all();
        $mac = $this->_concat_fields($contexte);
        $key = Commande::getUsableKey(config('services.monetico.key'));
        $hash = strtoupper(hash_hmac('sha1', $mac, $key));

        if ($hash !== $contexte['MAC']) {
            // Hashmac non valide
            Log::error('Callback Monetico MAC', $request->all());
            return $this->respondFailure();
        }

        // Retrouver la commande
        $idCommande = Hashids::decode($contexte['reference']);
        $commande = Commande::with(['site'])->where('id', $idCommande)->first();

        if ($commande === null) {
            // Commande introuvable
            Log::error('Callback Monetico ref commande', ['ID' => $contexte['reference']]);
            return $this->respondFailure();
        }

        // paiement accepté
        $commande->paiement()->create([
            'currency' => 'EUR',
            'amount' =>  Str::remove('EUR', $contexte['montant']),
            'PM' =>  $contexte['usage'], // Type de carte
            'CARDNO' =>  $contexte['cbmasquee'], // Numéro de la carte
            'ED' =>  $contexte['vld'], // Date d'expiration de la carte
            // 'CN' =>  $contexte[''], // Nom du titulaire de la carte
            'TRXDATE' =>  $contexte['date'], // Date de la demande d'autorisation
          //  'PAYID' =>  $contexte['numauto'], // Numéro de la transaction
            'PAYID' =>  $contexte['reference'],
            'BRAND' =>  $contexte['brand'], // Code de la carte utilisée
            'IPCTY' =>  $contexte['originetr'], // Code pays d'origine de la transaction
            'IP' => $contexte['ipclient'], // Adresse IP du client
        ]);

        try {
            // Create booking
            $presence = new PresenceQuery('CreateBooking');
            $res = $presence->request($commande->getCreateBookingParams());

            if ($res['success'] === true) {
                //@TODO créer job envoi mail pour que erreur d'envoi ne conditionne par le retour monetico

                // Enregistrer les infos CreateBooking
                $resa = $commande->reservation()->create([
                    'url' => $res['body']['url'],
                    'attestation' => $res['body']['attestation'],
                    'cgv' => $res['body']['cgv'],
                    'codes' => serialize($res['body']['codes']),
                    'num_souscription' => $res['body']['num_souscription'],
                    'ref_souscription' => $res['body']['ref_souscription'],
                    'date_souscription' => $res['body']['date_souscription'],
                    'statut' => $res['body']['statut'],
                ]);

                $client = resolve(NotificationClient::class);
                $res = $client->send($commande);

                $resa->notified_at = Carbon::now();
                $resa->save();

                $commande->email_id = $res->getMessageId();
                $commande->save();

                // return response()->json(['success' => true]);
                return $this->respondSuccess();
            }
        } catch (Exception $e) {
            Log::error('Confirm paiement : ' . $e->getMessage());

            Mail::to($commande->site->notifier)
                ->send(new ConfirmationEmailNotDelivered($commande));
        }

        return $this->respondFailure();
    }

    /**
     * Retourne la chaîne nécessaire au calcul du MAC
     * https://github.com/nursit/bank/blob/master/presta/cmcic/inc/cmcic.php
     *
     * @param  Array $contexte  La liste des paramètres retournés par Monetico
     */
    private function _concat_fields($contexte): string
    {

        $keys = [
            'TPE',
            'authentification',
            'bincb',
            'brand',
            'cbmasquee',
            'code-retour',
            'cvx',
            'date',
            'ecard',
            'hpancb',
            'ipclient',
            'modepaiement',
            'montant',
            'numauto',
            'originecb',
            'originetr',
            'reference',
            'texte-libre',
            'typecompte',
            'usage',
            'vld',
        ];

        // $keys = ['TPE', 'date', 'montant', 'reference'];
        $values = [];
        foreach ($keys as $key) {
            if (!isset($contexte[$key])) {
                $contexte[$key] = '';
            }
            $values[] = "$key=" . $contexte[$key];
        }

        return implode('*', $values);
    }

    private function respondFailure()
    {
        return response("version=2\ncdr=1", 200)
            ->header('Content-Type', 'text/plain');
    }

    private function respondSuccess()
    {
        return response("version=2\ncdr=0", 200)
            ->header('Content-Type', 'text/plain');
    }
}
