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
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->string('spot_number'); // e.g., "A-01", "B-15"
            $table->enum('type', ['regular', 'electric', 'handicap'])->default('regular');
            $table->boolean('is_active')->default(true); // for maintenance
            $table->timestamps();

            $table->unique(['zone_id', 'spot_number']); // spot numbers unique per zone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spots');
    }
};
