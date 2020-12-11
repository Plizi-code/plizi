<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('notice')->nullable();
            $table->string('primary_image')->nullable();
            $table->string('url')->nullable();
            $table->string('website')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_verified')->default(false);
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
        Schema::dropIfExists('communities');
    }
}
