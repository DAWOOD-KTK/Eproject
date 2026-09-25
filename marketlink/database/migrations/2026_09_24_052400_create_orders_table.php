<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('market_id')->nullable()->constrained('markets')->onDelete('set null');
            $table->decimal('total_amount', 10, 2);
            $table->enum('order_status', ['placed', 'accepted', 'ready_for_pickup', 'completed', 'cancelled'])->default('placed');
            $table->date('pickup_date');
            $table->string('pickup_time_slot'); // e.g. "09:00 AM - 10:00 AM"
            $table->dateTime('cutoff_time')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('farmer_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->string('cancelled_by')->nullable(); // 'customer', 'farmer', 'admin'
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
