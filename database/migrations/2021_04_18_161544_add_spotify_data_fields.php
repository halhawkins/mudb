<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpotifyDataFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spotify_top200', function (Blueprint $table) {
            $table->text('spotify_data')->after('spotify_id'); // use this for field after specific column.
        });
        Schema::table('spotify_viral50', function (Blueprint $table) {
            $table->text('spotify_data')->after('spotify_id'); // use this for field after specific column.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('spotify_top200', function (Blueprint $table) {
            $table->dropColumn('spotify_data');
        });
        Schema::table('spotify_viral50', function (Blueprint $table) {
            $table->dropColumn('spotify_data');
        });
            }
}
