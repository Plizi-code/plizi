<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAndFillGeoTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_countries', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->string('title_ru', 60);
            $table->string('title_ua', 60);
            $table->string('title_en', 60);
            $table->integer('created_at')->default(time());
            $table->integer('updated_at')->default(time());
        });
        Schema::create('geo_regions', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->unsignedInteger('country_id');
            $table->string('title_ru', 150);
            $table->string('title_ua', 150);
            $table->string('title_en', 150);
            $table->integer('created_at')->default(time());
            $table->integer('updated_at')->default(time());
        });
        Schema::create('geo_cities', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('region_id');
            $table->enum('important', ['f', 't']);
            $table->string('title_ru', 150);
            $table->string('area_ru', 150);
            $table->string('region_ru', 150);
            $table->string('title_ua', 150);
            $table->string('area_ua', 150);
            $table->string('region_ua', 150);
            $table->string('title_en', 150);
            $table->string('area_en', 150);
            $table->string('region_en', 150);
            $table->integer('created_at')->default(time());
            $table->integer('updated_at')->default(time());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('geo_countries');
        Schema::dropIfExists('geo_regions');
        Schema::dropIfExists('geo_cities');
        Schema::enableForeignKeyConstraints();
    }
}
