<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommutityLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('communities', static function (Blueprint $table) {
            $table->dropColumn('location');

            $table->unsignedInteger('geo_city_id')->nullable();
            $table->foreign('geo_city_id')->references('id')->on('geo_cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communities', static function (Blueprint $table) {
        });
    }
}
