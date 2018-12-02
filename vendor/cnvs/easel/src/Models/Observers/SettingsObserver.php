<?php

namespace Canvas\Models\Observers;

use Canvas\Models\Settings;

class SettingsObserver
{
    /**
     * 'saved' event handler for the Settings model.
     * Forgets the cache every time a new row is created or updated.
     *
     * @param  Settings $settings
     * @return void
     */
    public function saved(Settings $settings)
    {
        Settings::forget();
    }
}
