<?php

namespace App\Providers;

use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Cache\Repository;
use App\Auth\UserProviderDecorator;

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

        Auth::provider('cached', function ($app, array $config) {
            $hasher = $app->make(HasherContract::class);
            $provider = new EloquentUserProvider($hasher, $config['model']);
            $cache = $app->make(Repository::class);
            return new UserProviderDecorator($provider, $cache);
        });
    }
}
