<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommandeCse;
use App\Models\Destination;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CseController extends Controller
{
   
    public function voyage(Request $request)
    {
        $valide = $request->validate(['ref' => 'required|min:1']);

        if ($valide) {
            // Vérifier si on a la commande en BDD
            $commande = CommandeCse::where('numero', '=', $request->input('ref'))->first();

            if ($commande) {
                return response()->json(['success' => true, 'body' => unserialize($commande->contenu)]);
            }

            // Nouvelle recherche WS CSE
            $client = new Client();
            $url = "https://ws.cselignes.com".'?methode=getcommanddetail&data={"auth":{"id":"';
            $url .= env('WS_CSE_ID').'","password":"';
            $url .= env('WS_CSE_KEY').'"},"ref_commande":"';
            $url .= $request->input('ref').'"}';

            $res = $client->request('GET', $url);

            if ($res->getStatusCode() === 200) {
                $body = json_decode((string) $res->getBody());

                if (property_exists($body, 'numCommande')) {

                   

                    $accompagnants = [];
                    for ($i = 1; $i < count($body->passagers); $i++) {
                        $accompagnants[] = [
                            'nom' => $body->passagers[$i]->nom,
                            'prenom' => $body->passagers[$i]->prenom,
                       //     'dateNaissance' =>  \Carbon\Carbon::createFromFormat('d/m/Y', $body->passagers[$i]->date_naissance)->isoFormat('d/m/Y')
                            'dateNaissance' =>  $body->passagers[$i]->date_naissance
                            
                        ];
                    }

                    // Formater les données à retourner
                    $destination = null;
                    $pays = Destination::where('alpha2', 'LIKE', '%'.$body->paysDestination.'%')->first();

                    if ($pays) {
                        $destination = [
                            'iso' => $pays->alpha2,
                            'name' => $pays->nom,
                            'slug' => $pays->slug,
                            'pre' => $pays->article,
                        ];
                    }
                    
                    if($body->civilite == 'M') {
                        $civilite = $body->civilite = "1";
                    }else if($body->civilite == 'Mme') {
                        $civilite = $body->civilite = "2";
                    }else{
                        $civilite = $body->civilite = "null";
                    }

               
              
                    $date = $body->date_naissance;
                  //  $newDate = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->isoFormat('d/m/Y');
            
                    $data = [
                        'numero' => $body->numCommande,
                        'source' => $body->source,
                        'transport' => $body->typeTransport,
                        'depart' => $body->dateDepart,
                        'retour' => $body->dateRetour,
                        'montant' => $body->prixPayeAgent,
                        'destination' => $destination,
                        'civilite' => $civilite,
                     // 'civilite' => $body->civilite,
                        'telephone' => $body->telephone,
                        'adresse1' => $body->adresse1,
                        'adresse2' => $body->adresse2,
                        'codePostal' => $body->codePostal,
                        'ville' => $body->ville,
                        'date_naissance' => $date,
                        'voyageur' => [
                            'nom' => $body->passagers[0]->nom,
                            'prenom' => $body->passagers[0]->prenom,
                            'email' => $body->adresseMail,
                            
                        ],
                        'accompagnants' => $accompagnants,
                    ];

                    //Enregistrer la commande
                   

                    CommandeCse::create([
                        'numero' => $body->numCommande,
                        'contenu' => serialize($data),
                    ]);

                    return response()->json(['success' => true, 'body' => $data]);
                }

                // Retourner l'erreur
                if (property_exists($body, 'codeRetour')) {
                    if (property_exists($body, 'messagePublic')) {
                        return response()->json(['success' => false, 'erreurs' => [$body->messagePublic]]);
                    }

                    return response()->json(['success' => false, 'erreurs' => ['Une erreur est survenue']]);
                }
            } else {
                return response()->json(['success' => false, 'erreurs' => ['Une erreur est survenue']]);
            }
        }

        return response()->json(['success' => false, 'erreurs' => ['Le numéro de commande est obligatoire']]);
    }
}
