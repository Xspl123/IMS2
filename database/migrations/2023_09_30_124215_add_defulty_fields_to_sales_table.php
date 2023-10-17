<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefultyFieldsToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('defulty_product_name')->nullable();
            $table->string('defulty_product_sn')->nullable();
            $table->string('defulty_product_vendor')->nullable();
            $table->string('defulty_product_remark')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('defulty_product_name');
            $table->dropColumn('defulty_product_sn');
            $table->dropColumn('defulty_product_vendor');
            $table->dropColumn('defulty_product_remark');
        });
    }
}
