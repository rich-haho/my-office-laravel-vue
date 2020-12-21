<?php

use App\Models\Locale;
use Illuminate\Database\Seeder;

class LocalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Locale::create(['name' => 'fr']);
        Locale::create(['name' => 'en']);
    }
}
