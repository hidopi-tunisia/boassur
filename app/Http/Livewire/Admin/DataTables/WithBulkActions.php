<?php

namespace App\Http\Livewire\Admin\DataTables;

trait WithBulkActions
{
    public $selectPage = false;

    public $selectAll = false;

    public $selected = [];

    public function renderingWithSorting()
    {
        if ($this->selectAll) {
            $this->selectPageRows();
        }
    }

    /**
     * Select page updated hook
     */
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->selectPageRows();
        } else {
            $this->resetSelected();
        }
    }

    /**
     * Selected updated hook
     */
    public function updatedSelected()
    {
        $this->selectAll = false;
    }

    public function selectPageRows()
    {
        $this->selected = $this->rows->pluck('id')->map(fn ($id) => (string) $id);
    }

    public function selectAll()
    {
        $this->selectAll = true;
    }

    public function getSelectedRowsQuery()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAll, fn ($query) => $query->whereKey($this->selected));
    }

    public function resetSelected()
    {
        $this->selected = [];
    }
}
