<?php

namespace App\Listeners;

use App\Events\AdminSaving;

class SyncAdminPartner
{
    /**
     * Handle the event.
     *
     * @param AdminSaving $event
     * @return void
     */
    public function handle(AdminSaving $event)
    {
        $admin = $event->admin;

        // Checking if the administrator is linked to one partner then syncing both records.
        if ($admin->partner()->count() === 1) {
            $admin->partner()->update([
                'email' => $admin->email
            ]);
        }
    }
}
