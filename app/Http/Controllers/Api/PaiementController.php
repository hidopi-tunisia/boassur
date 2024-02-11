<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmationEmailNotDelivered;
use App\Models\Commande;
use App\Models\Paiement;
use App\PresenceWS\PresenceQuery;
use App\Services\SendinBlue\Client as NotificationClient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;

class PaiementController extends Controller
{
    /**
     * Callback confirmation paiement
     *
     * @param  Request  $request
     * @return int
     */
    public function confirm(Request $request)
    {
        // S'assurer que la requête vient de Ogone

        // Retrouver la commande
        $idCommande = Hashids::decode($request->input('orderID'));
        $commande = Commande::with(['site'])->where('id', $idCommande)->first();

        if ($commande === null) {
            Log::error('Callback Ingenico', ['ID' => $request->input('orderID')]);

            return 1;
        }

        if (intval($request->input('STATUS'), 10) === 9) {
            try {
                // paiement accepté
                $paiementModel = new Paiement();
                $commande->paiement()->create($request->only($paiementModel->getFillable()));

                // Create booking
                $presence = new PresenceQuery('CreateBooking');
                $res = $presence->request($commande->getCreateBookingParams());

                if ($res['success'] === true) {
                    // Enregistrer les infos CreateBooking
                    $resa = $commande->reservation()->create([
                        'url' => $res['body']['url'],
                        'attestation' => $res['body']['attestation'],
                        'cgv' => $res['body']['cgv'],
                        'codes' => serialize($res['body']['codes']),
                        'num_souscription' => $res['body']['num_souscription'],
                        'ref_souscription' => $res['body']['ref_souscription'],
                        'date_souscription' => $res['body']['date_souscription'],
                        'statut' => $res['body']['statut'],
                    ]);

                    $client = resolve(NotificationClient::class);
                    $res = $client->send($commande);

                    $resa->notified_at = Carbon::now();
                    $resa->save();

                    $commande->email_id = $res->getMessageId();
                    $commande->save();

                    return 0;
                }

                Log::error('Create booking', ['content' => $res['body']]);
            } catch (Exception $e) {
                Mail::to($commande->site->notifier)
                    ->send(new ConfirmationEmailNotDelivered($commande));
            }
        }

        Log::error('Callback Ingenico', $request->all());

        return 1;
    }
}
