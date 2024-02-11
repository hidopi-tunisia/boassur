<?php

namespace App\Http\Livewire\Admin;

use App\Http\Livewire\Admin\DataTables\WithBulkActions;
use App\Http\Livewire\Admin\DataTables\WithCachedRows;
use App\Http\Livewire\Admin\DataTables\WithPerPagePagination;
use App\Http\Livewire\Admin\DataTables\WithSorting;
use App\Models\Rappel;
use Livewire\Component;

class Rappels extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $showFilters = false;

    public $filters = [
        'nom' => null,
        'telephone' => null,
    ];

    public Rappel $editing;

    protected $queryString = [];

    /**
     * Les règles de validation
     */
    public function rules()
    {
        return [
            'editing.nom' => 'required',
            'editing.telephone' => 'required',
            'editing.date_rappel' => 'required',
            'editing.heure_rappel' => 'sometimes',
        ];
    }

    public function mount()
    {
        $this->editing = Rappel::make();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getRowsQueryProperty()
    {
        $query = Rappel::query()
            ->when($this->filters['nom'], fn ($query, $nom) => $query->where('nom', 'like', '%'.$nom.'%'))
            ->when($this->filters['telephone'], fn ($query, $telephone) => $query->where('telephone', 'like', '%'.$telephone.'%'));

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
            $this->editing = Rappel::make();
        }

        $this->showEditModal = true;
    }

    /**
     * Modale d'édition
     *
     * @param  Rappel  $rappel La demande à éditer
     */
    public function edit(Rappel $rappel)
    {
        $this->resetErrorBag();
        $this->useCachedRows();

        // Ecraser le voyageur en cours si différent de celui à éditer
        if ($this->editing->isNot($rappel)) {
            $this->editing = $rappel;
        }

        $this->showEditModal = true;
    }

    /**
     * Enregistrement des modifications
     */
    public function save()
    {
        $this->validate();

        $this->editing->save();

        $this->showEditModal = false;
    }

    public function exportSelected()
    {
        return response()->streamDownload(function () {
            echo $this->getSelectedRowsQuery()->toCsv();
        }, 'rappels.csv');
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
        return view('admin.rappels', [
            'rappels' => $this->rows,
        ])
            ->layout('admin.layouts.app');
    }
}
