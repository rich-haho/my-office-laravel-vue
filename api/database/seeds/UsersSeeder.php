<?php

use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Random client users
        factory(User::class, 5)->create()->each(function($user) {
            factory(State::class, 1)->create([
                'user_id'   => $user->id,
            ]);
        });

        // Defined user for easier login
        $user = factory(User::class)->create([
          'email'       => 'user@tcm.com',
          'client_id'   => 1,
        ]);
        factory(State::class, 1)->create([
            'user_id'   => $user->id,
            'site_id'   => 1,
            'locale_id' => 1,
        ]);

        $superUser = factory(User::class)->create([
            'email'       => 'superuser@tcm.com',
            'client_id'   => 1,
        ]);

        factory(State::class, 1)->create([
            'user_id'   => $superUser->id,
            'site_id'   => 1,
            'locale_id' => 1,
        ]);

        $superUser->superuser = true;
        $superUser->save();
    }
}
