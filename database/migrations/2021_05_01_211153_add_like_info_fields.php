<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLikeInfoFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likes', function(Blueprint $table){
            $table->string('item_name')->nullable();
            $table->string('artist')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('likes', function(Blueprint $table){
            $table->dropColumn('item_name');
            $table->dropColumn('artist');
        });
    }
}
