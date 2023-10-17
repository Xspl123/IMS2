<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandIdToSales extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('product_brand_id')->nullable(); // Change the data type as needed
            $table->foreign('product_brand_id')->references('id')->on('product_brands');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['product_brand_id']);
            $table->dropColumn('product_brand_id');
        });
    }
}
