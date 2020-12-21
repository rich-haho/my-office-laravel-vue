<?php

use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Asset;
use App\Models\Site;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use App\Imports\PartnersImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductsImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //import products form cvs files(remove cvs header)
        Excel::import(new ProductsImport, 'database/csv/clients_services_products.csv', null, \Maatwebsite\Excel\Excel::CSV);

        //link partner in products from cvs files(remove cvs header)
        Excel::import(new PartnersImport, 'database/csv/product_partner.csv', null, \Maatwebsite\Excel\Excel::CSV);
    }
}
