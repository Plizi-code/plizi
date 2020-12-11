<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersBlacklistedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_blacklisted', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id', 24);
            $table->integer('blacklisted_id', false, true);
            $table->integer('created_at', false, true);
            $table->integer('updated_at', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_blacklisted');
    }
}
