<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_product_id');
            $table->unsignedBigInteger('fk_category_id');
            $table->timestamps();

            $table->foreign('fk_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('fk_category_id')->references('id')->on('taxo_lists')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
