<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunityMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_members', function (Blueprint $table) {
            $table->integer('community_id');
            $table->string('user_id', 24);
            $table->enum('role', [
                'author',
                'admin',
                'user'
            ])->default('user');
            $table->integer('created_at')->default(time());
            $table->integer('updated_at')->default(time());

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
        Schema::dropIfExists('community_members');
    }
}
