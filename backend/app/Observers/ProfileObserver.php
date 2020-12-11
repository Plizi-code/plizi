<?php

namespace App\Observers;

use App\Events\Registered;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Models\Profile;
use Illuminate\Support\Facades\Cache;

class ProfileObserver
{
    /**
     * Handle the profile "created" event.
     *
     * @param  Profile  $profile
     * @return void
     */
    public function created(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "updated" event.
     *
     * @param  Profile  $profile
     * @return void
     */
    public function updated(Profile $profile)
    {
        if (Cache::has('id-' . $profile->user->id)) {
            Cache::forget('id-' . $profile->user->id);
        }
        event(new UserUpdated($profile->user->with('profile')->first()));
    }

    /**
     * Handle the profile "deleted" event.
     *
     * @param  Profile  $profile
     * @return void
     */
    public function deleted(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "restored" event.
     *
     * @param  Profile  $profile
     * @return void
     */
    public function restored(Profile $profile)
    {
        //
    }

    /**
     * Handle the profile "force deleted" event.
     *
     * @param  Profile  $profile
     * @return void
     */
    public function forceDeleted(Profile $profile)
    {
        //
    }
}
