<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null'); // Link to clients table
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade'); // Link to cars table
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status')->default('pending'); // e.g., pending, confirmed, active, completed, cancelled
            $table->decimal('total_price', 10, 2)->nullable(); // Example precision
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
