<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledsToPostAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_attachments', function (Blueprint $table) {
            $table->string('image_normal_path')->nullable();
            $table->string('image_medium_path')->nullable();
            $table->string('image_thumb_path')->nullable();

            $table->integer('image_normal_width')->nullable();
            $table->integer('image_normal_height')->nullable();
            $table->integer('image_thumb_width')->nullable();
            $table->integer('image_thumb_height')->nullable();
            $table->integer('image_medium_width')->nullable();
            $table->integer('image_medium_height')->nullable();
            $table->integer('image_original_width')->nullable();
            $table->integer('image_original_height')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_attachments', function (Blueprint $table) {
            $table->dropColumn('image_normal_path');
            $table->dropColumn('image_medium_path');
            $table->dropColumn('image_thumb_path');

            $table->dropColumn('image_normal_width');
            $table->dropColumn('image_normal_height');
            $table->dropColumn('image_thumb_width');
            $table->dropColumn('image_thumb_height');
            $table->dropColumn('image_medium_width');
            $table->dropColumn('image_medium_height');
            $table->dropColumn('image_original_width');
            $table->dropColumn('image_original_height');
        });
    }
}
