<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompanies extends Migration
{
    public function up() {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('tax_number');
            $table->string('phone');
            $table->string('city', 255);
            $table->string('billing_address', 255);
            $table->string('country', 255);
            $table->string('postal_code', 64);
            $table->string('employees_size', 255);
            $table->string('fax');
            $table->string('description', 255);
            $table->boolean('is_active')->nullable()->default(1);
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('companies');
    }
}
