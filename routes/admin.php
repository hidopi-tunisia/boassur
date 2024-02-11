<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('app.admin_domain'))->name('admin.')->middleware('web')->group(function () {
    Route::get('/telecharger/{ref}', [\App\Http\Controllers\ContratController::class, 'telecharger'])->name('din');

    Route::get('/conditions-generales/{ref}', [\App\Http\Controllers\ContratController::class, 'cgv'])->name('cgv');

    Route::middleware(['auth:admin'])->group(function () {
        Route::redirect('/', '/dashboard', 301);

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/admin/ajout', \App\Http\Livewire\Admin\Admin\Register::class)->name('admin.ajout');
        Route::get('/admin/edition/{admin}', \App\Http\Livewire\Admin\Admin\Edit::class);
     //   Route::post('/select', \App\Http\Livewire\Admin\Commandes::class);
        Route::get('/destinations', \App\Http\Livewire\Admin\Destinations::class)->name('destinations.liste');
        Route::get('/sites', \App\Http\Livewire\Admin\Sites::class)->name('sites.liste');
        Route::get('/voyageurs', \App\Http\Livewire\Admin\Voyageurs::class)->name('voyageurs.liste');
        Route::get('/commandes', \App\Http\Livewire\Admin\Commandes::class)->name('commandes.liste');
        Route::get('/contrats', \App\Http\Livewire\Admin\Contrats::class)->name('contrats.liste');
        Route::get('/rappels', \App\Http\Livewire\Admin\Rappels::class)->name('rappels.liste');

    });
});
