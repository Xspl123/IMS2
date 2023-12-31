<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('category');
            $table->integer('count');
            $table->integer('price');
            $table->string('rented')->nullable();
            $table->string('rent_start_date')->nullable();
            $table->string('rent_end_date')->nullable();
            $table->boolean('is_active')->nullable()->default(1);
            $table->unsignedInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
