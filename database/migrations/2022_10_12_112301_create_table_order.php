<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('queue_number');
            $table->string('order_number',250)->unique();
            
            $table->string('contact_email',250);
            $table->string('contact_phone',250);
            $table->string('shipping_country',250);
            $table->string('shipping_first_name',250);
            $table->string('shipping_last_name',250);
            $table->longText('shipping_address');
            $table->string('shipping_city',250);
            $table->string('shipping_province',250);
            $table->string('shipping_postal_code',250);
            $table->string('shipping_label_place',250)->nullable();
            $table->string('shipping_note_address',250)->nullable();

            $table->string('courier_agent',250)->nullable();
            $table->string('courier_agent_service',250)->nullable();
            $table->string('courier_agent_service_desc',250)->nullable();
            $table->string('courier_estimate_delivered',250)->nullable();
            $table->string('courier_resi_number',250)->nullable();
            $table->integer('courier_cost')->nullable();

            $table->string('payment_method',250)->nullable();
            $table->string('payment_refrence_code',250)->nullable();
            $table->string('payment_snap_token',250)->nullable();

            $table->longText('invoice_note')->nullable();
             $table->longText('delivery_order_note')->nullable();

            $table->unsignedBigInteger('fk_user_id')->nullable();
            $table->string('payment_status',250)->nullable();
            $table->enum('status', ['ORDER','PAYMENT','PROCESS','SHIPPED','COMPLETE'])->default('ORDER');
            $table->foreign('fk_user_id')->references('id')->on('users')->onDelete('restrict');

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
        Schema::dropIfExists('orders');
    }
}
