<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use App\Exports\ExportUser;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ContratController extends Controller
{
   
    
    public function telecharger($ref)
    {
        $id = Hashids::decode($ref);
        $contrat = Contrat::where('id', $id)->first();

        if ($contrat === null) {
            return abort(404);
        }

        if ($contrat !== null && Storage::exists($contrat->url_fiche) === false) {
            return abort(404);
        }

        $nom = Str::slug($contrat->libelle).'.pdf';

        return response()->file(Storage::path($contrat->url_fiche));
    }

    public function cgv($ref)
    {
        $id = Hashids::decode($ref);
        $contrat = Contrat::where('id', $id)->first();

        if ($contrat === null) {
            return abort(404);
        }

        if ($contrat !== null && Storage::exists($contrat->url_cgv) === false) {
            return abort(404);
        }

        $nom = Str::slug($contrat->libelle).'-cgv.pdf';

        return response()->file(Storage::path($contrat->url_cgv));
    }
}
