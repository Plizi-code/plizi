<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRelationshipInProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'relationship')) {
                DB::statement('ALTER TABLE profiles CHANGE relationship relationship_id smallint(6)');
            }
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
            if (Schema::hasColumn('profiles', 'relationship_id')) {
                DB::statement('ALTER TABLE profiles CHANGE relationship_id relationship smallint(6)');
            }
        });
    }
}
