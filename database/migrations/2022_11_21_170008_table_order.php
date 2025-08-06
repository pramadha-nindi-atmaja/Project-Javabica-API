<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('contact_billing_phone',250)->nullable()->after('shipping_note_address');
               $table->string('billing_country',250)->nullable()->after('contact_billing_phone');
               $table->string('billing_first_name',250)->nullable()->after('billing_country');
               $table->string('billing_last_name',250)->nullable()->after('billing_first_name');
               $table->longText('billing_address')->after('billing_last_name');
               $table->string('billing_city',250)->nullable()->after('billing_address');
               $table->string('billing_province',250)->nullable()->after('billing_city');
               $table->string('billing_postal_code',250)->nullable()->after('billing_province');
               $table->string('billing_label_place',250)->nullable()->after('billing_postal_code');
               $table->string('billing_note_address',250)->nullable()->after('billing_label_place');
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
