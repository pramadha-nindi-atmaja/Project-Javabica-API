<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('sku',250)->unique();
            $table->unsignedBigInteger('fk_attribute_parent_id')->nullable();
            $table->unsignedBigInteger('fk_attribute_child_id')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('price');
            $table->integer('discount')->nullable();
            $table->unsignedBigInteger('fk_product_id');
            $table->longText('image_path')->nullable();
            $table->enum('is_ignore_stock', ['ACTIVE', 'INACTIVE'])->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->nullable();
            $table->timestamps();

            $table->foreign('fk_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('fk_attribute_parent_id')->references('id')->on('taxo_lists')->onDelete('restrict');
            $table->foreign('fk_attribute_child_id')->references('id')->on('taxo_lists')->onDelete('restrict');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
