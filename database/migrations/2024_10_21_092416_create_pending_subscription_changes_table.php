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
        Schema::create('pending_subscription_changes', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->foreignId('new_plan_id')->constrained('plans');
            $table->unsignedInteger('new_number_of_users');
            $table->enum('new_payment_frequency', ['monthly', 'yearly']);
            $table->decimal('new_discount_percentage', 5, 2)->default(0);
            $table->decimal('new_total_cost', 10, 2);
            $table->date('change_effective_date');
            $table->enum('status', ['pending', 'applied', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_subscription_changes');
    }
};
