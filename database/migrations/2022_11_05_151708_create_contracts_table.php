<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('car_id')->nullable()->index('fk_table1_cars_idx');
            $table->unsignedBigInteger('client_id')->nullable()->index('fk_table1_clients_idx');
            $table->unsignedBigInteger('sub_client_id')->nullable()->index('fk_contracts_sub_clients');
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
