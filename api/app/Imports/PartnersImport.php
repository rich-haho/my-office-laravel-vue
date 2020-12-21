<?php

namespace App\Imports;

use App\Models\Admin;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class PartnersImport implements ToModel, WithCustomCsvSettings
{
    /**
     * remove cvs header
     * @param array $row
     * @return Product
     */
    public function model(array $row)
    {
        /** @var Partner $partner */
        $partner = Partner::where('name', '=', $row['1'])->first();
        if (null === $partner) {
            DB::connection()->enableQueryLog();
            $partner = new Partner();
            $partner->fill([
                'name'  =>  $row['1'] ?? '',
                'email' =>  $row['5'] ?? ''
            ]);
            $partner->save();

            $admin = Admin::create([
                'name' => $partner->name,
                'email' => $partner->email,
                'password' => Hash::make(Str::random()),
            ]);
            $admin->assignRole('Partenaire');
            $partner->admin_id = $admin->id;
            $partner->save();
        }

        /** @var Product $product*/
        $product = Product::where('name->fr', '=', $row['9'])->first();
        if (!empty($product)) {
            $product->fill([
                'partner_id' => $partner->id
            ])->save();
        }
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
