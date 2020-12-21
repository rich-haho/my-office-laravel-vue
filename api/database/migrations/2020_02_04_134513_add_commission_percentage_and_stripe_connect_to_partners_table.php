<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionPercentageAndStripeConnectToPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->decimal('commission_percentage',5 ,2)
                ->default(0)
                ->after('private_description');
            $table->string('connected_stripe_id')
                ->after('commission_percentage')
                ->nullable()
                ->default(null);
            $table->boolean('enable_stripe_connect')
                ->after('connected_stripe_id')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('commission_percentage');
            $table->dropColumn('connected_stripe_id');
            $table->dropColumn('enable_stripe_connect');
        });
    }
}
