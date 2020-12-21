<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Client;
use App\Models\Service;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

/**
 * @method import(string $string , $null , string $CSV)
 */
class ProductsImport implements ToModel, WithCustomCsvSettings
{
    /**
    * remove cvs header
    * @param array $row
    */

    public function model(array $row)
    {
        $price = str_replace(',', '.', str_replace(' ', '', str_replace('CHF', '', $row['3'])));
        if (!is_numeric($price)) {
            $price = floatval(0);
        }

        /** @var Client $client*/
        $client = Client::where('name', '=', $row['1'])->first();
        if (null === $client) {
            DB::connection()->enableQueryLog();
            $client = new Client();
            $client->name = $row['1'] ?? '';
            $client->save();
            $site = new Site;
            $site->name = $row['1'] ?? '';
            $site->client_id = $client->id;
            $site->save();
        }

        /** @var Service $service*/
        $service = Service::where('name->fr', '=', $row['2'])->first();
        if (null === $service) {
            $service = new Service;
            $service->setTranslation('name', 'fr', $row['2']);
            $service->save();
        }
        /** @var Site $site*/
        $site = Site::where('client_id', '=', $client->id)->first();
        $service->sites()->syncWithoutDetaching([$site->id]);
        $product = new Product;
        $product->setTranslation('name', 'fr', $row['4']);
        $product->setTranslation('name', 'en', $row['5']);
        $product->setTranslation('description', 'fr', $row['6']);
        $product->setTranslation('description', 'en', $row['7']);
        $product->service_id = $service->id;
        $product->price = $price;
        $product->price_reduction = 0;
        $product->manual_product = 0;
        $product->moment_product = 0;
        $product->currency_id = 2;
        $product->save();
        $product->sites()->syncWithoutDetaching([$site->id]);

        return $product;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter' => ';',
        ];
    }
}
