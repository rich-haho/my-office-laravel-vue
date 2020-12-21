<?php

use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Asset;
use App\Models\Site;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use App\Imports\PartnersImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create random 10 products
        factory(Product::class, 10)->create()->each(function(Product $product) {
            $product
                ->assets()
                ->saveMany(factory(Asset::class, 1)->make());

            $product->tags()->saveMany(factory(Tag::class, 1)->make());

            $product->sites()->sync(Site::all()->random()->id);
        });
    }
}
