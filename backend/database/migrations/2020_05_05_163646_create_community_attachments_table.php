<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_attachments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id', 24);
            $table->integer('community_id')->nullable();
            $table->string('original_name')->default('');
            $table->string('path')->default('');
            $table->string('mime_type')->default('');
            $table->integer('size')->default(0);
            $table->integer('updated_at');
            $table->integer('created_at');

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

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_attachments');
    }
}
