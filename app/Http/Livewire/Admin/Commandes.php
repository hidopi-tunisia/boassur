<?php



namespace App\Http\Livewire\Admin;



use App\Http\Livewire\Admin\DataTables\WithBulkActions;

use App\Http\Livewire\Admin\DataTables\WithCachedRows;

use App\Http\Livewire\Admin\DataTables\WithPerPagePagination;

use App\Http\Livewire\Admin\DataTables\WithSorting;

use App\Models\Commande;

use Livewire\Component;

use Illuminate\Http\Request;

use Carbon\Carbon;




class Commandes extends Component

{

    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;



    public $showEditModal = false;



    public $showDeleteModal = false;



    public $showFilters = false;



    public $filters = [

        'ogone' => null,

        'quote_id' => null,

        'nom' => null,

        'email' => null,
'datefrom'=>null,

        'souscription' => null,

        'created_at' => null,
    ];



    public Commande $editing;



    protected $queryString = [];



    public function mount()

    {

        $this->editing = Commande::make();

        $this->sorts = ['created_at' => 'desc'];

    }



    public function updatedFilters()

    {

        $this->resetPage();

    }

   

    public function getRowsQueryProperty(Request $request)

    {
        
     

        $query = Commande::query()

            ->when($this->filters['ogone'], fn ($query, $ogone) => $query->whereHas('paiement', function ($query) use ($ogone) {

                $query->where('PAYID', 'like', '%'.$ogone.'%');

            }))

            ->when($this->filters['souscription'], fn ($query, $num) => $query->whereHas('reservation', function ($query) use ($num) {

                $query->where('num_souscription', 'like', '%'.$num.'%');

            }))

            ->when($this->filters['email'], fn ($query, $email) => $query->whereHas('voyageur', function ($query) use ($email) {

                $query->where('email', 'like', '%'.$email.'%');

            }))
           // ->when($this->filters['datefrom'])->where('created_at','>',Carbon::parse(($request->datefrom)->format('Y-m-d')))

         //  ->when($this->filters['datefrom'])->where('created_at','<=', Carbon::createFromFormat('m/d/Y',$request->datefrom)->format('d/m/Y'))
           // ->when($this->filters['datefrom'], fn ($query, $request) => $query->whereHas('voyageur', function ($query) use ($request) {

              //  $query->where(Carbon::createFromDate('created_at')->format('Y-m-d'), '<=', Carbon::createFromDate($request->datefrom)->format('Y-m-d'));

          //  }))
           

          //  ->when($this->filters['daterange'])->whereBetween('created_at', [($request->start)->format('Y-m-d') , ($request->end)->format('Y-m-d')])
          

            
          
            //->where('created_at', '<=', Carbon::parse($request->datefrom)->format('Y-m-d')
          //  date('2023-05-23')
          //  Carbon::createFromFormat($request->datefrom)->format('Y-m-d') )

          

        //  ->whereBetween('created_at', [$daterange['start'], $daterange['end']])

        
            ->when($this->filters['nom'], fn ($query, $nom) => $query->whereHas('voyageur', function ($query) use ($nom) {

                $query->where('nom', 'like', '%'.$nom.'%')

                    ->orWhere('prenom', 'like', '%'.$nom.'%');

            }));
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

     * Modale d'édition

     *

     * @param  Commande  $commande  La commande à éditer

     */

    public function show(Commande $commande)

    {

        $this->editing = $commande->load('destination', 'voyageur', 'accompagnants');



        $this->showEditModal = true;

    }



    public function exportSelected()

    {

        return response()->streamDownload(function () {

            echo $this->getSelectedRowsQuery()->toCsv();

        }, 'clients.csv');

    }



    public function resetFilters()

    {

        $this->reset('filters');

    }



    public function render()

    {

        return view('admin.commandes', [

            'commandes' => $this->rows,

        ])

            ->layout('admin.layouts.app');

    }

}

