<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add loyalty_points column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('loyalty_points')->default(0)->after('role');
            $table->string('loyalty_tier')->default('bronze')->after('loyalty_points'); // bronze, silver, gold, platinum
        });

        // Loyalty points transactions table
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('points'); // Can be positive (earned) or negative (redeemed)
            $table->enum('type', ['earned', 'redeemed', 'bonus', 'expired', 'adjustment']);
            $table->string('description');
            $table->integer('balance_after')->default(0); // Running balance after transaction
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index('expires_at');
        });

        // Rewards catalog table
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('points_required');
            $table->enum('type', ['discount', 'free_item', 'voucher', 'merchandise']);
            $table->decimal('discount_amount', 10, 2)->nullable(); // For discount type
            $table->integer('discount_percentage')->nullable(); // For percentage discount
            $table->string('free_item_id')->nullable(); // Menu ID for free item
            $table->integer('stock')->default(-1); // -1 for unlimited
            $table->integer('max_redemption_per_user')->default(0); // 0 for unlimited
            $table->boolean('is_active')->default(true);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('points_required');
        });

        // User reward redemptions
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reward_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('points_spent');
            $table->string('code')->unique(); // Unique redemption code
            $table->enum('status', ['pending', 'used', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('code');
        });

        // Loyalty tiers configuration
        Schema::create('loyalty_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // bronze, silver, gold, platinum
            $table->integer('min_points')->default(0); // Minimum points to reach this tier
            $table->integer('points_multiplier')->default(100); // 100 = 1x, 150 = 1.5x, 200 = 2x
            $table->integer('discount_percentage')->default(0); // Permanent discount for tier
            $table->text('benefits')->nullable(); // JSON array of benefits
            $table->string('badge_color')->default('#CD7F32'); // Color for UI
            $table->string('badge_icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_tiers');
        Schema::dropIfExists('reward_redemptions');
        Schema::dropIfExists('rewards');
        Schema::dropIfExists('loyalty_points');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points', 'loyalty_tier']);
        });
    }
};
