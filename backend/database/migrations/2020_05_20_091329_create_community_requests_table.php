<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_requests', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id', 24);
            $table->integer('community_id');
            $table->text('description');
            $table->smallInteger('status');
            $table->integer('updated_at');
            $table->integer('created_at');
        });

        Schema::table('community_requests', static function(Blueprint $table) {
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
        Schema::dropIfExists('community_requests');
    }
}
