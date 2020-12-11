<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateImageUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_uploads', function (Blueprint $table) {
            $table->integer('likes')->default(0);
            $table->string('creatable_id');
            $table->string('creatable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_uploads', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('creatable_id');
            $table->dropColumn('creatable_type');
        });
    }
}
