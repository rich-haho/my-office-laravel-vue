<?php

use App\Models\Site;
use Illuminate\Database\Seeder;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Give data to first client
        factory(Site::class, 3)->create(['client_id' => '1']);

        // Random sites
        factory(Site::class, 10)->create();
    }
}
