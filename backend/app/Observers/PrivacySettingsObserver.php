<?php

namespace App\Observers;

use App\Models\User\PrivacySettings;
use Illuminate\Support\Facades\Cache;

class PrivacySettingsObserver
{
    /**
     * Handle the privacy settings "created" event.
     *
     * @param  \App\Models\User\PrivacySettings  $privacySettings
     * @return void
     */
    public function created(PrivacySettings $privacySettings)
    {
        //
    }

    /**
     * Handle the privacy settings "updated" event.
     *
     * @param  \App\Models\User\PrivacySettings  $privacySettings
     * @return void
     */
    public function updated(PrivacySettings $privacySettings)
    {
        if (Cache::has('id-' . $privacySettings->user->id)) {
            Cache::forget('id-' . $privacySettings->user->id);
        }
    }

    /**
     * Handle the privacy settings "deleted" event.
     *
     * @param  \App\Models\User\PrivacySettings  $privacySettings
     * @return void
     */
    public function deleted(PrivacySettings $privacySettings)
    {
        //
    }

    /**
     * Handle the privacy settings "restored" event.
     *
     * @param  \App\Models\User\PrivacySettings  $privacySettings
     * @return void
     */
    public function restored(PrivacySettings $privacySettings)
    {
        //
    }

    /**
     * Handle the privacy settings "force deleted" event.
     *
     * @param  \App\Models\User\PrivacySettings  $privacySettings
     * @return void
     */
    public function forceDeleted(PrivacySettings $privacySettings)
    {
        //
    }
}
