<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('taxo_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent')->nullable();
            $table->integer('taxonomy_ref_key')->nullable();
            $table->string('taxonomy_name',250)->nullable();
            $table->longText('taxonomy_description')->nullable();
            $table->string('taxonomy_slug',250)->nullable();
            $table->unsignedBigInteger('taxonomy_type')->nullable();
            $table->longText('taxonomy_image')->nullable();
            $table->integer('taxonomy_sort')->nullable();
            $table->enum('taxonomy_status', ['ACTIVE', 'INACTIVE'])->nullable();
            $table->timestamps();

            $table->foreign('taxonomy_type')->references('id')->on('taxo_types')->onDelete('restrict');
            $table->foreign('parent')->references('id')->on('taxo_lists')->onDelete('restrict');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxo_lists');
    }
}
