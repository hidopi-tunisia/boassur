<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom', 'url', 'actif', 'objet_email', 'contenu_email', 'sender', 'email', 'notifier'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Ne pas permettre la suppression si le site est utilisé
            if ($model->commandes()->count() !== 0) {
                return false;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }
}
