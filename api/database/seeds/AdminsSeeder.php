<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrator
        factory(Admin::class)->create([
            'email'     => 'admin@tcm.com',
            'admin'     => true,
        ]);
    }
}
