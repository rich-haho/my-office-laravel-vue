<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPartnersFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->unsignedBigInteger ( 'client_id' )->nullable()->change();
            $table->string('name')->nullable ()->change ();
            $table->text ( 'address' )->nullable()->change();
            $table->string ( 'phone' )->nullable()->change();
            $table->string ( 'email' )->nullable()->change();
            $table->string ( 'contact_name' )->nullable()->change();
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
            $table->unsignedBigInteger('client_id')->change();
            $table->string('name')->change();
            $table->text('address')->change();
            $table->string('phone')->change();
            $table->string('email')->change();
            $table->string('contact_name')->change();
        });
    }
}
