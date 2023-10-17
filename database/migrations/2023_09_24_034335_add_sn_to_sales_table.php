<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSnToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('sn')->after('id'); // Add 'sn' column after 'id'
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('sn');
        });
    }
    
}
