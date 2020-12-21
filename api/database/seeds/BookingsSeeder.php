<?php

use App\Models\Booking;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Booking\Product::class, 20)->create()->each(function ($product) {
            $booking = factory(Booking::class)->make();
            $product->bookings()->save($booking);
            $product->product()->associate(\App\Models\Product::all()->random()->id);
            $product->save();
        });

        $role = Role::where('name', 'Administrateur')->first();
        $role->givePermissionTo('manage bookings');
    }
}
