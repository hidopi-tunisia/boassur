<?php

namespace App\Http\Livewire\Admin;

use App\Http\Livewire\Admin\DataTables\WithBulkActions;
use App\Http\Livewire\Admin\DataTables\WithCachedRows;
use App\Http\Livewire\Admin\DataTables\WithPerPagePagination;
use App\Http\Livewire\Admin\DataTables\WithSorting;
use App\Models\Site;
use Livewire\Component;

class Sites extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $showFilters = false;

    public $filters = ['nom' => null];

    public Site $editing;

    protected $queryString = [];

    /**
     * Les règles de validation
     */
    public function rules()
    {
        return [
            'editing.nom' => 'required',
            'editing.url' => 'required',
            'editing.objet_email' => 'required',
            'editing.contenu_email' => 'required',
            'editing.sender' => 'required',
            'editing.email' => 'required|email',
            'editing.notifier' => 'required|email',
        ];
    }

    public function mount()
    {
        $this->editing = Site::make();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getRowsQueryProperty()
    {
        $query = Site::query()
            ->when($this->filters['nom'], fn ($query, $nom) => $query->where('nom', 'like', '%'.$nom.'%'));

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

        // Ecraser le site en cours par une vide
        if ($this->editing->getKey()) {
            $this->editing = Site::make();
        }

        $this->showEditModal = true;
    }

    /**
     * Modale d'édition
     *
     * @param  Site  $site  Le site à éditer
     */
    public function edit(Site $site)
    {
        $this->resetErrorBag();
        $this->useCachedRows();

        // Ecraser le site en cours si différent de celui à éditer
        if ($this->editing->isNot($site)) {
            $this->editing = $site;
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
        }, 'sites.csv');
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
        return view('admin.sites', [
            'sites' => $this->rows,
        ])
            ->layout('admin.layouts.app');
    }
}
