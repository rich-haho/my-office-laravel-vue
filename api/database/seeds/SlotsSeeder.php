<?php

use App\Models\Slot;
use App\Models\SlotTime;
use App\Models\SlotBooking;
use Illuminate\Database\Seeder;

class SlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Slot::class, 20)->create();
        factory(SlotTime::class, 20)->create();
        factory(SlotBooking::class, 10)->create();
    }
}
