<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTabelMediaAlbum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_album', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            
            $table->string('judul');
            $table->string('slug');

            $table->integer('view')->default(0);
            $table->integer('komentar')->default(0);
            
            $table->integer('status')->default(0);

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
        Schema::dropIfExists('media_album');
    }
}
