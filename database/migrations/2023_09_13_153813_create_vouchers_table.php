<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description');
            $table->enum('type', ['1', '2'])->default('1')->comment('1-fixed, 2-percentage');
            $table->float('amount');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_usage')->nullable();
            $table->enum('is_active', ['1', '2'])->default('1')->comment('1-active, 2-non active');
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
        Schema::dropIfExists('vouchers');
    }
}
