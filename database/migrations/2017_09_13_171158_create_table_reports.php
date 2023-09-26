<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('companies_id');
            $table->foreign('companies_id')->references('id')->on('companies')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unsignedInteger('clients_id');
            $table->foreign('clients_id')->references('id')->on('clients')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('reports');
    }
}
