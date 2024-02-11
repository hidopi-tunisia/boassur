<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * @const int MAX_PER_PAGE Le nombre maximal d'enregistrement à retourner
     */
    const MAX_PER_PAGE = 10;

    /**
     * Retourne la liste des destinations dont le nom commence par le terme donné
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = min($request->input('limit') ?? self::MAX_PER_PAGE, self::MAX_PER_PAGE);

        $destinations = Destination::when($request->input('terme'), fn ($query, $terme) => $query->where('nom', 'like', $terme.'%'))
            ->orderBy('nom', 'asc')
            ->simplePaginate($limit)
            ->appends(['terme' => $request->input('terme')]);

        return response()->json($destinations);
    }
}
