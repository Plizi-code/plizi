<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_attachments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id', 24);
            $table->integer('post_id')->nullable();
            $table->string('original_name')->default('');
            $table->string('path')->default('');
            $table->string('mime_type')->default('');
            $table->integer('size')->default(0);
            $table->integer('updated_at');
            $table->integer('created_at');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_attachments');
    }
}
