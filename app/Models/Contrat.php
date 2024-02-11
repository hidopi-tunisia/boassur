<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Vinkla\Hashids\Facades\Hashids;

class Contrat extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $fillable = ['quote_id', 'site_id', 'recap', 'libelle', 'url_fiche', 'url_cgv', 'ordre'];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->id);
    }
}
