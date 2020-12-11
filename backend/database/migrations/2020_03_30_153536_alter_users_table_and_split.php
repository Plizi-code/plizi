<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Profile;

class AlterUsersTableAndSplit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'lastname', 'birthday', 'city']);
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->string('user_id', 24);
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->enum('sex', array_keys(Profile::SEX_VARIANTS))->default(Profile::SEX_UNDEFINED);
            $table->date('birthday')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });

        Schema::table('profiles', function(Blueprint $table) {
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname');
            $table->string('lastname');
            $table->timestamp('birthday')->nullable();
            $table->string('city')->nullable();


        });

        Schema::dropIfExists('profiles');
    }
}
