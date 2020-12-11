<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProfileRenameFirstnameAndLastname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            DB::statement('ALTER TABLE profiles CHANGE firstname first_name VARCHAR(200)');
            DB::statement('ALTER TABLE profiles CHANGE lastname last_name VARCHAR(200)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            DB::statement('ALTER TABLE profiles CHANGE first_name firstname VARCHAR(200)');
            DB::statement('ALTER TABLE profiles CHANGE last_name lastname VARCHAR(200)');
        });
    }
}
