<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_uploads', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id', 24);
            $table->string('original_name')->default('');
            $table->string('path')->default('');
            $table->string('url')->default('');
            $table->string('mime_type')->default('');
            $table->integer('size')->default(0);
            $table->enum('tag', ['primary', 'secondary'])->default('secondary');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_uploads');
    }
}
