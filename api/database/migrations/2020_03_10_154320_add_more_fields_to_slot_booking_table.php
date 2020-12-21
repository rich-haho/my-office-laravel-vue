<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToSlotBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slot_booking', function (Blueprint $table) {
            $table->date('date')->after('quantity')->nullable();
            $table->time('start_time')->after('date')->nullable();
            $table->time('end_time')->after('start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slot_booking', function (Blueprint $table) {
            $table->dropColumn(['date', 'start_time', 'end_time']);
        });
    }
}
