<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ClientsSeeder::class);
         $this->call(SitesSeeder::class);
         $this->call(LocalesSeeder::class);
         $this->call(UsersSeeder::class);
         $this->call(AdminsSeeder::class);
         $this->call(EmailDomainsSeeder::class);
         $this->call(RolesAndPermissionsSeeder::class);
         $this->call(PartnersSeeder::class);
         $this->call(ServicesSeeder::class);
         $this->call(CurrenciesSeeder::class);
         $this->call(ProductsSeeder::class);
         $this->call(BookingsSeeder::class);
         $this->call(SlotsSeeder::class);
    }
}

