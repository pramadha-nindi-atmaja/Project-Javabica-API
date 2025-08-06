<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('only for exposed purpose');
            $table->longText('avatar')->nullable();
            $table->string('name',100);
            $table->string('last_name',100);
            $table->string('phone',20)->nullable()->unique();
            $table->string('email',100)->unique();
            $table->date('dob');
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->longText('erp_remember_token')->nullable()->comment('for store user token from api');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
