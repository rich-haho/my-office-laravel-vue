<?php

use App\Models\Admin;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Config\Repository as Config;

class ProductionSeeder extends Seeder
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
        $this->call(LocalesSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        //$this->call(ProductsImportSeeder::class);

        factory(Admin::class)->create([
            'name'      => 'Nawel Laroui',
            'email'     => 'nawel.laroui@fleximgroup.com',
            'admin'     => true,
            'password'  => Hash::make(substr(md5(rand()),0,10)),
        ]);
    }
}

