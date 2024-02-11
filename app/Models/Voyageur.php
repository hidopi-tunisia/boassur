<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class Voyageur extends Model
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    protected $appends = ['nom_complet', 'salutation'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['numero', 'civilite', 'nom', 'prenom', 'email', 'telephone', 'date_naissance', 'cp', 'ville', 'adresse', 'adresse2', 'pays_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commande(): BelongsTo
    {
        return $this->BelongsTo(Commande::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->BelongsTo(Destination::class, 'pays_id');
    }

    public function getNomCompletAttribute()
    {
        return trim($this->prenom.' '.$this->nom);
    }

    public function getAnniversaireAttribute()
    {
      //  return Carbon::createFromFormat('Y-m-d', $this->attributes['date_naissance'])->format('d/m/Y');
        return $this->attributes['date_naissance'];

    }

    public function getSalutationAttribute()
    {
        if (intval($this->civilite, 10) === 1) {
            return 'monsieur';
        }
        if (intval($this->civilite, 10) === 2) {
            return 'madame';
        }

        return '';
    }
}
