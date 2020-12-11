<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('author_id', 24);
            $table->string('title');
            $table->string('description');
            $table->string('creatable_id');
            $table->string('creatable_type')->nullable();
            $table->integer('updated_at')->default(time());
            $table->integer('created_at')->default(time());

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('photo_albums');
    }
}
