<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_themes', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('parent_id')->default(0);
            $table->string('name');
            $table->integer('updated_at');
            $table->integer('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_themes');
    }
}
