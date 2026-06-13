<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();

            // Customer snapshot at order time
            $table->string('name');
            $table->string('mobile', 20);
            $table->string('email');
            $table->text('delivery_address');
            $table->string('state');
            $table->string('district');
            $table->string('pin_code', 10);

            // Totals
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_charges', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2);

            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])
                  ->default('pending');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
