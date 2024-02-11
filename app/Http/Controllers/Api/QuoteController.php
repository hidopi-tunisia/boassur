<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchQuoteRequest;
use App\Models\Contrat;
use App\PresenceWS\PresenceQuery;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
    /**
     * Search Quote vers le WS Presence
     *
     * @param  SearchQuoteRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchQuoteRequest $request)
    {
        $presence = new PresenceQuery('SearchQuote');
        $res = $presence->request($request->all());
        if (array_key_exists('success', $res) && $res['success'] === true) {
            $ids = Arr::pluck($res['body'], 'ref');

            if (count($ids) > 0) {
                $contrats = Contrat::whereIn('quote_id', $ids)
                    ->where('site_id', $request->site_id)
                    ->orderBy('ordre')
                    ->get();

                if ($contrats->count() > 0) {
                    $assurances = [];
                    $items = collect($res['body'])->groupBy('ref')->toArray();

                    foreach ($contrats as $contrat) {
                        $fiche = null;
                        if (! empty($contrat->url_fiche) && Storage::exists($contrat->url_fiche)) {
                            $fiche = env('APP_URL').'/telecharger/'.$contrat->hashid;
                        }

                        $cgv = null;
                        if (! empty($contrat->url_cgv) && Storage::exists($contrat->url_cgv)) {
                            $cgv = env('APP_URL').'/conditions-generales/'.$contrat->hashid;
                        }

                        array_push($assurances, array_merge(
                            $items[$contrat->quote_id][0],
                            [
                                'libelle' => $contrat->libelle,
                                'recap' => htmlspecialchars_decode($contrat->recap),
                                'fiche' => $fiche,
                                'cgv' => $cgv,
                            ]
                        ));
                    }

                    return response()->json(['success' => true, 'body' => $assurances]);
                }
            }
        }

        return response()->json(['success' => false, 'erreurs' => ['Aucune assurance ne correspond à vos critères.']]);
    }
}
