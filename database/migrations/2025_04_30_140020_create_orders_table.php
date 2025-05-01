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
            $table->string('transaction_code', 10)->unique();
            $table->bigInteger('quantity', false)->default(0);
            $table->string('price', 15);
            $table->string('payment_url')->nullable();

            $table->enum('status', ['settlement', 'pending', 'cash', 'cancel', 'refund'])->default('pending');

            $table->foreignId('user_id')->constrained('users');

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
