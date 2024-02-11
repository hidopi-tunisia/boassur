<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Accompagnant extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['commande_id', 'nom', 'prenom', 'date_naissance'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function getNomCompletAttribute()
    {
        return trim($this->attributes['prenom'].' '.$this->attributes['nom']);
    }

    public function getAnniversaireAttribute()
    {
      //  return Carbon::createFromFormat('Y-m-d', $this->attributes['date_naissance'])->format('d/m/Y');
        return  $this->attributes['date_naissance'];

    }
}
