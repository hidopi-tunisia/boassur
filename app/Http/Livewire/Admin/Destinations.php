<?php

namespace App\Http\Livewire\Admin;

use App\Http\Livewire\Admin\DataTables\WithBulkActions;
use App\Http\Livewire\Admin\DataTables\WithCachedRows;
use App\Http\Livewire\Admin\DataTables\WithPerPagePagination;
use App\Http\Livewire\Admin\DataTables\WithSorting;
use App\Models\Destination;
use Livewire\Component;

class Destinations extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $showFilters = false;

    public $filters = [
        'nom' => null,
        'alpha2' => null,
    ];

    public Destination $editing;

    protected $queryString = [];

    /**
     * Les règles de validation
     */
    public function rules()
    {
        return [
            'editing.nom' => 'required',
            'editing.article' => 'required',
            'editing.alpha2' => 'required|size:2|unique:destinations,alpha2,'.$this->editing->id,
        ];
    }

    public function mount()
    {
        $this->editing = Destination::make();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getRowsQueryProperty()
    {
        $query = Destination::query()
            ->when($this->filters['nom'], fn ($query, $nom) => $query->where('nom', 'like', '%'.$nom.'%'))
            ->when($this->filters['alpha2'], fn ($query, $alpha2) => $query->where('alpha2', '=', $alpha2));

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

        // Ecraser la destination en cours par une vide
        if ($this->editing->getKey()) {
            $this->editing = Destination::make();
        }

        $this->showEditModal = true;
    }

    /**
     * Modale d'édition
     *
     * @param  Destination  $destination  La destination à éditer
     */
    public function edit(Destination $destination)
    {
        $this->resetErrorBag();
        $this->useCachedRows();

        // Ecraser la destination en cours si différente de celle à éditer
        if ($this->editing->isNot($destination)) {
            $this->editing = $destination;
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
        }, 'destinations.csv');
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
        return view('admin.destinations', [
            'destinations' => $this->rows,
        ])
            ->layout('admin.layouts.app');
    }
}
