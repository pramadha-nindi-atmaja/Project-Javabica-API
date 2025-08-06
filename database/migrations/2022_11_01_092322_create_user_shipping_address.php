<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserShippingAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',250);
            $table->string('last_name',250);
            $table->string('phone_number',50);
            $table->string('label_place',250)->nullable();
            $table->string('courier_note',250)->nullable();
            $table->longText('address');
            $table->integer('city')->comment('city id from raja ongkir');
            $table->string('city_label',250)->comment('city label from raja ongkir');
            $table->integer('province')->comment('province id from raja ongkir');
            $table->string('province_label',250)->comment('province label from raja ongkir');
            $table->unsignedBigInteger('fk_user_id')->nullable();
            $table->string('postal_code',50);
            $table->timestamps();
            
            $table->foreign('fk_user_id')->references('id')->on('users')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_shipping_addresses');
    }
}
