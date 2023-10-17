<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // Action description
            $table->text('data')->nullable(); // Additional data related to the action
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
