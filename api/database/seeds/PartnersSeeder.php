<?php

use App\Models\Site;
use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Partner::class, 20)->create()->each(function($partner) {
            $partner->sites()->sync(Site::all()->pluck('id'));
        });
    }
}
