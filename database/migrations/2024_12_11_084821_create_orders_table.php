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
            $table->timestamps();
            $table->text('order_number')->default('-');
            $table->text('address')->default('-');
            $table->text('phone')->default('-');
            $table->double('total_price')->default(0);
            $table->enum('status', [ 'initiated', 'paid','delivered'])->default('initiated');
            $table->foreignId("user_id") ->constrained();
            $table->foreignId("payment_id") ->constrained();
            $table->foreignId("city_id") ->constrained();


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
