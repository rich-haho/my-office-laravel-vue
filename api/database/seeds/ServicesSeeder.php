<?php
use App\Models\Service;
use App\Models\Asset;
use App\Models\Site;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siteIds = Site::all()->pluck('id');
        factory(Service::class, 10)->create()->each(function($service) use($siteIds) {
            $service
                ->assets()
                ->saveMany(factory(Asset::class, 1)->make());
            $service->sites()->attach($siteIds);
        });
    }
}
