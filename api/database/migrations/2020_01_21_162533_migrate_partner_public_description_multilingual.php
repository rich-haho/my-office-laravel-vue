<?php

use App\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigratePartnerPublicDescriptionMultilingual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $partners = Partner::all();
        foreach ($partners ?? [] as $partner) {
            $publicDescription = DB::table('partners')
                ->where('id', '=', $partner->id)
                ->pluck('public_description')[0];
            $partner->setTranslation('public_description', 'fr', $publicDescription ?? '');
            $partner->setTranslation('public_description', 'en', '');
            $partner->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
