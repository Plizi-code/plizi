<?php

namespace App\Providers;

use App\Models\Profile;
use App\Models\User\PrivacySettings;
use App\Observers\PrivacySettingsObserver;
use App\Observers\ProfileObserver;
use App\Services\SocialUserResolver;
use Coderello\SocialGrant\Resolvers\SocialUserResolverInterface;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        SocialUserResolverInterface::class => SocialUserResolver::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Profile::observe(ProfileObserver::class);
        PrivacySettings::observe(PrivacySettingsObserver::class);
    }
}
