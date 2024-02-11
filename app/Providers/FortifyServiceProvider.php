<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (request()->isAdmin()) {
            config(['fortify.domain' => config('app.admin_domain')]);
            config(['fortify.guard' => 'admin']);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            if ($request->isAdmin()) {
                $user = Admin::where('email', $request->email)->first();
            } else {
                $user = User::where('email', $request->email)->first();
            }

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        // Fortify::loginView(function () {
        //     if (request()->isAdmin()) {
        //         return view('admin.auth.login');
        //     }

        //     return view('auth.login');
        // });

        if (request()->isAdmin()) {
            Fortify::viewPrefix('admin.auth.');
        } else {
            Fortify::viewPrefix('front.auth.');
        }
    }
}
