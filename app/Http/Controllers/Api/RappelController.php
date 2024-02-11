<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RappelRequest;
use App\Mail\NouveauRappel;
use App\Models\Rappel;
use Illuminate\Support\Facades\Mail;

class RappelController extends Controller
{
    public function store(RappelRequest $request)
    {
        $rappel = Rappel::create($request->only(['nom', 'date_rappel', 'heure_rappel', 'telephone']));

        Mail::to(env('MAIL_CSE_FROM_ADDRESS'))
            ->send(new NouveauRappel($rappel));

        return response()->json(['success' => true]);
    }
}
