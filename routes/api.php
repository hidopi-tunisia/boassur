<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('app.admin_domain'))->prefix('v1')->group(function () {
    Route::get('/destinations', 'App\Http\Controllers\Api\DestinationController@index');
    Route::post('/demande-de-rappel', 'App\Http\Controllers\Api\RappelController@store');
    Route::post('/search-quote', 'App\Http\Controllers\Api\QuoteController@index');
    Route::post('/cse/voyage', 'App\Http\Controllers\Api\CseController@voyage');
    Route::post('/commandes', 'App\Http\Controllers\Api\CommandeController@store');
    // Route::post('/paiements/confirmation', 'App\Http\Controllers\Api\PaiementController@confirm');
    Route::post('/paiements/confirmation', 'App\Http\Controllers\Api\MoneticoController@confirm');
    Route::post('/sendinblue', 'App\Http\Controllers\Api\SendinBlueWebhookController@update');
});
