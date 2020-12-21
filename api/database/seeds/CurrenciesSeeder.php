<?php

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create(['name' => 'EUR', 'code' => 'eur']);
        Currency::create(['name' => 'CHF', 'code' => 'chf']);
    }
}
