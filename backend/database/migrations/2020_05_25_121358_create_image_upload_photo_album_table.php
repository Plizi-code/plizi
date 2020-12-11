<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageUploadPhotoAlbumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_upload_photo_album', function (Blueprint $table) {
            $table->unsignedBigInteger('photo_album_id');
            $table->integer('image_upload_id');
            $table->integer('updated_at')->default(time());
            $table->integer('created_at')->default(time());

            $table->foreign('photo_album_id')
                ->references('id')
                ->on('photo_albums')
                ->onDelete('cascade');
            $table->foreign('image_upload_id')
                ->references('id')
                ->on('image_uploads')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_upload_photo_album');
    }
}
