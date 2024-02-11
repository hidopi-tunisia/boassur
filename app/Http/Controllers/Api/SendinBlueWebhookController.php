<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class SendinBlueWebhookController extends Controller
{
    /**
     * Email delivre
     *
     * @param  Request  $request
     */
    public function update(Request $request)
    {
        if ($request->has('message-id')) {
            $commande = Commande::where('email_id', $request->input('message-id'))
                ->first();

            if ($commande !== null) {
                $commande->email_statut = $request->input('event');
                $commande->email_date = $request->input('date');
                $commande->save();
            }
        }
    }
}
