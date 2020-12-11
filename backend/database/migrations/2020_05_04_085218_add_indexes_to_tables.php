<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('friendships', function (Blueprint $table) {
            $table->index(['sender_id', 'sender_type']);
        });

        Schema::table('friendships', function (Blueprint $table) {
            $table->index(['recipient_id', 'recipient_type']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['notifiable_id', 'notifiable_type', 'read_at']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index(['postable_id', 'postable_type']);
        });

        Schema::table('community_members', function (Blueprint $table) {
            $table->index(['community_id', 'user_id', 'role']);
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->index(['user_id']);
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
