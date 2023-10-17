<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_logs', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->text('context');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('custom_logs');
    }
}
