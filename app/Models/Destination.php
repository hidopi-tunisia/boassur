<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Destination extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['alpha2', 'nom', 'article', 'slug'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Ne pas permettre la suppression si la destination est utilisée
            if ($model->commandes()->count() !== 0) {
                return false;
            }
        });

        static::saving(function ($model) {
            $model->slug = Str::slug($model->nom, '-');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
}
