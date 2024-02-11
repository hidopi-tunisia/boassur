<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'cgv', 'attestation', 'codes', 'num_souscription', 'ref_souscription', 'date_souscription', 'statut', 'notified_at'];

    protected $appends = ['creation', 'notification'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function getCreationAttribute()
    {
        if ($this->date_souscription) {
            return Carbon::createFromFormat('Y-m-d', $this->date_souscription)->format('d/m/Y');
        }

        return $this->date_souscription;
    }

    public function getNotificationAttribute()
    {
        if ($this->notified_at) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->notified_at)->format('d/m/Y H:i:s');
        }

        return $this->notified_at;
    }
}
