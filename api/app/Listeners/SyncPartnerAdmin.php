<?php

namespace App\Listeners;

use App\Events\PartnerSaving;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SyncPartnerAdmin
{
    /**
     * Handle the event.
     *
     * @param PartnerSaving $event
     * @return int|boolean
     */
    public function handle(PartnerSaving $event)
    {
        $partner = $event->partner;

        // Checking if the administrator is linked to one partner then syncing both records.
        if ($partner->admin()->count() === 1) {
            return $partner->admin()->update(
                [
                    'email' => $partner->email
                ]
            );
        }

        // If there are no admin record sync with the model, then we create an admin with the
        // correct name and email, random password and remember token.
        $admin = $partner->admin()->create([
            'name' => $partner->name,
            'email' => $partner->email,
            'password' => Hash::make(Str::random()),
            'remember_token' => Str::random(10)
        ]);

        // Assigning Partner Role to the administrator user.
        $admin->assignRole('Partenaire');
        $partner->admin_id = $admin->id;

        return true;
    }
}
