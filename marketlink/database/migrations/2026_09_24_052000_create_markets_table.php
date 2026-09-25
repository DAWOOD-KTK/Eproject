<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('market_name');
            $table->text('address');
            $table->string('city')->default('Metropolis');
            $table->string('operating_days'); // e.g. "Saturday, Sunday"
            $table->string('timings'); // e.g. "07:00 AM - 02:00 PM"
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('map_provider')->default('OpenStreetMap');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('markets');
    }
};
