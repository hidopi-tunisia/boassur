<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandeRequest;
use App\Models\Commande;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;

class CommandeControllerOgone extends Controller
{
    /**
     * Crée une réservation
     *
     * @param  CommandeRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CommandeRequest $request)
    {
        $destination = Destination::where('alpha2', $request->input('code_pays_destination'))->select('id')->first();

        // Numéro de commande CSE
        $ref = $request->input('site_id') === '2' && $request->has('reference') ? $request->input('reference') : null;

        // Créer la commande
        $commande = Commande::create([
            'destination_id' => $destination->id,
            'site_id' => $request->input('site_id'),
            'quote_id' => $request->input('assurance')['id'],
            'quote_nom' => $request->input('assurance')['nom'],
            'quote_priceline' => $request->input('assurance')['priceline'],
            'depart' => $request->input('date_depart'),
            'retour' => $request->input('date_retour'),
            'prix_voyage' => $request->input('prix_voyage'),
            'montant' => $request->input('assurance')['prix'],
            'reference' => $ref,
        ]);

        if ($commande) {
            $voyageur = $request->input('voyageur');
            $voyageur['pays_id'] = null;
            $pays = Destination::where('alpha2', $voyageur['pays'])->first();

            if ($pays !== null) {
                $voyageur['pays_id'] = $pays->id;
            }

            $commande->voyageur()->create($voyageur);

            foreach ($request->input('accompagnants') as $accompagnant) {
                $commande->accompagnants()->create($accompagnant);
            }

            return response()->json([
                'success' => true,
                'body' => ['shasign' => $commande->getShasign(), 'id' => $commande->hashid, 'url' => $commande->site->url],
            ]);
        }

        return response()->json([
            'success' => false,
            'erreurs' => ['Une erreur est survenue'],
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
