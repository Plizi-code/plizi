<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTablesToImageUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_uploads', static function (Blueprint $table) {
            $table->integer('image_original_width')->nullable();
            $table->integer('image_original_height')->nullable();
            $table->string('image_normal_path')->nullable();
            $table->integer('image_normal_width')->nullable();
            $table->integer('image_normal_height')->nullable();
            $table->string('image_medium_path')->nullable();
            $table->integer('image_medium_width')->nullable();
            $table->integer('image_medium_height')->nullable();
            $table->string('image_thumb_path')->nullable();
            $table->integer('image_thumb_width')->nullable();
            $table->integer('image_thumb_height')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_uploads', function (Blueprint $table) {
            $table->dropColumn([
                'image_original_width',
                'image_original_height',
                'image_normal_path',
                'image_normal_width',
                'image_normal_height',
                'image_medium_path',
                'image_medium_width',
                'image_medium_height',
                'image_thumb_path',
                'image_thumb_width',
                'image_thumb_height',
            ]);
        });
    }
}
