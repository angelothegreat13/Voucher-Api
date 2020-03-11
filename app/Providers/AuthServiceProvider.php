<?php

namespace App\Providers;

use Carbon\Carbon;

use Laravel\Passport\Passport;

use Illuminate\Support\Facades\Gate;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        
        // \Carbon\Carbon::now()->addMinute();

        // Passport::personalAccessClientId('7');

        // Passport::tokensExpireIn(now()->addMinutes(1500));

        // Passport::refreshTokensExpireIn(now()->addMinute(1500));

        // Passport::personalAccessTokensExpireIn(now()->addMinute(1500));
    }
}
