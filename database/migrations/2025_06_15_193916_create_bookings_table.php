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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('car_id')->constrained();
            $table->dateTime('arrival_date');
            $table->dateTime('departure_date');
            $table->enum('status', ['reserved', 'arrived', 'departed', 'no_show'])->default('reserved');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->decimal('price', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
