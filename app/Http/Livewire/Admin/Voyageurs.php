<?php

namespace App\Http\Livewire\Admin;

use App\Http\Livewire\Admin\DataTables\WithBulkActions;
use App\Http\Livewire\Admin\DataTables\WithCachedRows;
use App\Http\Livewire\Admin\DataTables\WithPerPagePagination;
use App\Http\Livewire\Admin\DataTables\WithSorting;
use App\Models\Voyageur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Voyageurs extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $showFilters = false;

    public $filters = [
        'nom' => null,
        'email' => null,
        'cp' => null,
        'ville' => null,
        'telephone' => null,
        'adresse' => null,
    ];

    public Voyageur $editing;

    protected $queryString = [];

    /**
     * Les règles de validation
     */
    public function rules()
    {
        return [
            'editing.nom' => 'required',
            'editing.email' => 'required|email|unique:users,email,'.$this->editing->id,
            'editing.password' => 'sometimes',
            'editing.civilite' => 'required',
            'editing.prenom' => 'required',
            'editing.telephone' => 'required',
            'editing.anniversaire' => 'required',
            'editing.cp' => 'required|size:5',
            'editing.ville' => 'required',
            'editing.adresse' => 'required',
            'editing.adresse2' => 'sometimes',
            'editing.pays' => 'sometimes',
        ];
    }

    public function mount()
    {
        $this->editing = Voyageur::make();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getRowsQueryProperty()
    {
        $query = Voyageur::query()
            ->when($this->filters['nom'], fn ($query, $nom) => $query->where('nom', 'like', '%'.$nom.'%'))
            ->when($this->filters['adresse'], fn ($query, $adresse) => $query->where('adresse', 'like', '%'.$adresse.'%'))
            ->when($this->filters['email'], fn ($query, $email) => $query->where('email', 'like', '%'.$email.'%'))
            ->when($this->filters['telephone'], fn ($query, $telephone) => $query->where('telephone', 'like', '%'.$telephone.'%'))
            ->when($this->filters['cp'], fn ($query, $cp) => $query->where('cp', 'like', $cp.'%'));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = ! $this->showFilters;
    }

    /**
     * Modale de création
     */
    public function create()
    {
        $this->useCachedRows();

        // Ecraser la client en cours par une vide
        if ($this->editing->getKey()) {
            $this->editing = Voyageur::make();
        }

        $this->showEditModal = true;
    }

    /**
     * Modale d'édition
     *
     * @param  User  $voyageur Le voyageur à éditer
     */
    public function edit(Voyageur $voyageur)
    {
        $this->resetErrorBag();
        $this->useCachedRows();

        // Ecraser le voyageur en cours si différent de celui à éditer
        if ($this->editing->isNot($voyageur)) {
            $this->editing = $voyageur;
        }

        $this->showEditModal = true;
    }

    /**
     * Enregistrement des modifications
     */
    public function save()
    {
        $this->validate();

        // Ajouter un mot de passe au hazard
        if (empty($this->editing->password)) {
            $this->editing->password = Hash::make(Str::random(40));
        }

        $this->editing->save();

        $this->showEditModal = false;
    }

    public function exportSelected()
    {
        return response()->streamDownload(function () {
            echo $this->getSelectedRowsQuery()->toCsv();
        }, 'clients.csv');
    }

    public function deleteSelected()
    {
        $this->getSelectedRowsQuery()->delete();

        $this->resetSelected();

        $this->showDeleteModal = false;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function render()
    {
        return view('admin.clients', [
            'clients' => $this->rows,
        ])
            ->layout('admin.layouts.app');
    }
}
