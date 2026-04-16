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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete('cascade');
            $table->foreignId("bike_id")->constrained()->onDelete('cascade');
            $table->foreignId("pickup_station_id")->constrained('stations');
            $table->foreignId("return_station_id")->nullable()->constrained('stations')->nullOnDelete();
            $table->timestamp('rented_at')->nullable();
            $table->timestamp('return_at')->nullable();
            $table->integer('price')->nullable();
            $table->integer('total')->nullable();
            $table->enum('status', ['active', 'return'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
