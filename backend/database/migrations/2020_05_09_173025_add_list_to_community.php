<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListToCommunity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('communities', 'type')) {
            Schema::table('communities', static function (Blueprint $table) {
                $table->smallInteger('type');
                $table->integer('theme_id');
                $table->smallInteger('privacy');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communities', static function (Blueprint $table) {
            $table->dropColumn(['type', 'theme_id', 'privacy']);
        });
    }
}
