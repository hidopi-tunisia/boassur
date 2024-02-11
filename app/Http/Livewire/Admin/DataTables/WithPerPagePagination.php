<?php

namespace App\Http\Livewire\Admin\DataTables;

use Livewire\WithPagination;

trait WithPerPagePagination
{
    use WithPagination;

    public $perPage = 10;

    public function mountWithPerPagePagination()
    {
        $this->perPage = session()->get('perPage', $this->perPage);
    }

    public function updatedPerPage($value)
    {
        session()->put('perPage', $value);

        $this->resetPage();
    }

    public function applyPagination($query)
    {
        return $query->paginate($this->perPage);
    }
}
