<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLocationStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_stores', function (Blueprint $table) {
            $table->id();
            $table->string('name',250)->nullable();
            $table->integer('fk_province')->nullable();
            $table->string('image',250)->nullable();
            $table->longText('description',250);
            $table->string('embed_map',250)->nullable();
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
        Schema::dropIfExists('location_stores');
    }
}
