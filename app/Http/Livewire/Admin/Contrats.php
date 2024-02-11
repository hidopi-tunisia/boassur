<?php

namespace App\Http\Livewire\Admin;

use App\Http\Livewire\Admin\DataTables\WithBulkActions;
use App\Http\Livewire\Admin\DataTables\WithCachedRows;
use App\Models\Contrat;
use App\Models\Site;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Contrats extends Component
{
    use WithFileUploads, WithBulkActions, WithCachedRows;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $fiche;

    public $cgv;

    public Contrat $editing;

    public $sites;

    protected $queryString = [];

    public function updateTaskOrder($items)
    {
        Contrat::setNewOrder(Arr::pluck($items, 'value'));
    }

    public function rules()
    {
        return [
            'editing.site_id' => ['required', 'exists:sites,id'],
            'editing.quote_id' => ['required', 'unique:contrats,quote_id,'.$this->editing->id],
            'editing.libelle' => ['sometimes'],
            'editing.recap' => ['sometimes'],
            'editing.url_fiche' => ['sometimes'],
            'editing.url_cgv' => ['sometimes'],
        ];
    }

    public function mount()
    {
        $this->editing = Contrat::make();
        $this->sites = Site::orderBy('nom', 'asc')->get(['id', 'nom'])->toArray();
    }

    public function getRowsQueryProperty()
    {
        return Contrat::query();
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->orderBy('ordre')->get();
        });
    }

    /**
     * Modale de création
     */
    public function create()
    {
        $this->useCachedRows();

        // Ecraser le contrat en cours par une vide
        if ($this->editing->getKey()) {
            $this->editing = Contrat::make();
        }

        $this->showEditModal = true;
    }

    /**
     * Modale d'édition
     *
     * @param  Contrat  $contrat  Le contrat à éditer
     */
    public function edit(Contrat $contrat)
    {
        $this->resetErrorBag();
        $this->useCachedRows();

        // Ecraser le contrat en cours si différent de celui à éditer
        if ($this->editing->isNot($contrat)) {
            $this->editing = $contrat;
        }

        $this->fiche = null;
        $this->cgv = null;

        $this->showEditModal = true;
    }

    /**
     * Enregistrement des modifications
     */
    public function save()
    {
        $this->validate();

        if ($this->fiche !== null) {
            if (Storage::exists($this->editing->url_fiche)) {
                Storage::delete($this->editing->url_fiche);
            }

            $this->editing->url_fiche = $this->fiche->store('url_fiche');
            $this->fiche = null;
        }

        if ($this->cgv !== null) {
            if (Storage::exists($this->editing->url_cgv)) {
                Storage::delete($this->editing->url_cgv);
            }

            $this->editing->url_cgv = $this->cgv->store('url_cgv');
            $this->cgv = null;
        }

        $this->editing->save();

        $this->showEditModal = false;
    }

    public function confirmDelete(Contrat $contrat)
    {
        $this->resetErrorBag();
        $this->useCachedRows();

        // Ecraser le contrat en cours si différent de celui à éditer
        if ($this->editing->isNot($contrat)) {
            $this->editing = $contrat;
        }

        $this->showDeleteModal = true;
    }

    public function deleteSelected()
    {
        if (Storage::exists($this->editing->url_fiche)) {
            Storage::delete($this->editing->url_fiche);
        }

        if (Storage::exists($this->editing->url_cgv)) {
            Storage::delete($this->editing->url_cgv);
        }

        $this->editing->delete();

        $this->showDeleteModal = false;
    }

    /**
     * Suppression de la fiche DIN
     */
    public function deleteFiche()
    {
        if (
            $this->editing->url_fiche !== null &&
            Storage::exists($this->editing->url_fiche)
        ) {
            Storage::delete($this->editing->url_fiche);
        }

        $this->editing->url_fiche = null;
        $this->editing->save();
    }

    /**
     * Suppression du CGV
     */
    public function deleteCgv()
    {
        if (
            $this->editing->url_cgv !== null &&
            Storage::exists($this->editing->url_cgv)
        ) {
            Storage::delete($this->editing->url_cgv);
        }

        $this->editing->url_cgv = null;
        $this->editing->save();
    }

    public function render()
    {
        return view('admin.contrats', [
            'contrats' => $this->rows,
        ])
            ->layout('admin.layouts.app');
    }
}
