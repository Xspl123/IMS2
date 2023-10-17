<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChalansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chalans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('rent_id');
            $table->string('approved_By'); 
            $table->string('defulty_product_name');
            $table->string('defulty_product_sn');
            $table->string('defulty_product_vendor');
            $table->string('defulty_product_remark');
            $table->string('replacement_product_item');
            $table->string('replacement_product_serial');
            $table->string('replacement_product_vendor');
            $table->string('replacement_to_custmor');
            $table->string('replacement_Remark');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chalans');
    }
}
