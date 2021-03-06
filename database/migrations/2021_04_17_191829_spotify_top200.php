<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SpotifyTop200 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_top200', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // $table->collation('utf8_general_ci');
            $table->bigIncrements('id');
            $table->unsignedInteger('position');
            $table->string('track_name');
            $table->string('artist');
            $table->unsignedInteger('streams');
            $table->string('spotify_id');
            $table->timestamps();
        });
       //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('spotify_top200');
    }
}
