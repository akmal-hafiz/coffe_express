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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->json('items'); // Store cart items as JSON
            $table->decimal('total_price', 10, 2);
            $table->enum('pickup_option', ['pickup', 'delivery'])->default('pickup');
            $table->string('payment_method');
            $table->enum('status', ['pending', 'preparing', 'ready', 'completed'])->default('pending');
            $table->integer('estimated_time')->nullable(); // in minutes
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
