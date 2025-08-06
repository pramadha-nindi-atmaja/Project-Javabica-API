<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_product_id')->nullable();
            $table->unsignedBigInteger('fk_variant_id')->nullable();
            $table->string('product_name',250);
            $table->string('image',250)->nullable();
            $table->string('sku',250);
            $table->longText('variant_description')->nullable();
            $table->integer('qty');
            $table->integer('acctual_price');
            $table->integer('discount_price')->nullable();
            $table->integer('purchase_price');
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('fk_order_id')->nullable();
           
            $table->timestamps();
            $table->foreign('fk_order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('fk_product_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('fk_variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
