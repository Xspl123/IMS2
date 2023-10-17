<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandAndCategoryToProducts extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add product_brand_id and product_category_id fields
            $table->unsignedBigInteger('product_brand_id');
            $table->unsignedBigInteger('product_category_id');

            // Define foreign keys (if you have brand and category tables)
            // $table->foreign('product_brand_id')->references('id')->on('brands');
            // $table->foreign('product_category_id')->references('id')->on('categories');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverse the changes in the down method
            $table->dropColumn('product_brand_id');
            $table->dropColumn('product_category_id');
        });
    }
}

