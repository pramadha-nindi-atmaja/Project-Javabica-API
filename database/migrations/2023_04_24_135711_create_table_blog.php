<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->longText('cover')->nullable();
            $table->string('title',250)->nullable();
            $table->longText('short_desc',250)->nullable();
            $table->longText('long_desc')->nullable();
            $table->longText('fk_category')->nullable();
            $table->enum('status', ['PUBLISH', 'DRAFT']);  
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
        Schema::dropIfExists('blogs');
    }
}
