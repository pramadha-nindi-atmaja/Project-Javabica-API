<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',250);
            $table->string('slug',250);
            $table->longText('product_description')->nullable();
            $table->longText('product_information')->nullable();
            $table->longText('meta_keywords')->nullable()->comment('word untuk bagian meta head tags');
            $table->longText('meta_description')->nullable()->comment('word untuk bagian meta head tags');
            $table->longText('meta_title')->nullable()->comment('word untuk bagian meta head tags'); 
            $table->enum('is_freeshiping', ['ACTIVE', 'INACTIVE'])->default('INACTIVE')->after('type_size');
            $table->longText('tags')->nullable();

            $table->integer('weight')->comment('berat'); 
            $table->enum('type_weight', ['GRAM', 'KG']);
            $table->integer('size_long')->comment('panjang'); 
            $table->integer('size_wide')->comment('lebar');
            $table->integer('size_tall')->comment('tinggi');
            $table->enum('type_size', ['CM', 'M']);
            $table->integer('sort')->comment('nomor_urut')->after('type_size'); 

            $table->enum('status', ['PUBLISH', 'INACTIVE', 'DRAFT'])->nullable()->default('DRAFT');
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
        Schema::dropIfExists('products');
    }
}
