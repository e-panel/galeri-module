<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTabelMediaGaleri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_galeri', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');

            $table->string('caption')->nullable();
            $table->string('foto')->nullable();
            $table->string('url_video')->nullable();
            
            $table->integer('jenis')->nullable();
            $table->integer('id_operator')->nullable();
            $table->integer('id_album')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_galeri');
    }
}
