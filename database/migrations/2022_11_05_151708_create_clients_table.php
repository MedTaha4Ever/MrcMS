<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {            $table->bigIncrements('id');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('cin', 45)->unique();
            $table->string('permis', 50)->unique();
            $table->date('date_permis');
            $table->date('b_date');
            $table->string('adrs')->nullable();
            $table->string('notes', 1000)->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->bigInteger('contract_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
