<?php

use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Config\Repository as Config;

class SuperUserSeeder extends Seeder
{
    /**
     * Implementation of Laravel Configuration Repository.
     *
     * @var Config
     */
    private $config;


    /**
     * ProductionSeeder constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $superUser = factory(User::class)->create([
            'name' => 'Super Utilisateur',
            'email' => $this->config->get('zenoffice.superuser.mail'),
            'client_id' => 1,
        ]);

        factory(State::class, 1)->create([
            'user_id' => $superUser->id,
            'site_id' => null,
            'locale_id' => 1,
        ]);

        $superUser->superuser = true;
        $superUser->save();
    }
}

