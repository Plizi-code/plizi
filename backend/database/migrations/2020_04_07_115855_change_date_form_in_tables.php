<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDateFormInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_uploads', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        Schema::table('image_uploads', function (Blueprint $table) {
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('last_activity_dt');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('last_activity_dt');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
}
