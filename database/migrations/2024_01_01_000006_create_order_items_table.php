<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            // Nullable FKs — product/seller may be deleted later, but we keep the snapshot
            $table->foreignId('product_id')->nullable()->nullOnDelete()->constrained('products');
            $table->foreignId('seller_id')->nullable()->nullOnDelete()->constrained('users');

            // Snapshots so order history is accurate even if product changes
            $table->string('product_name');
            $table->string('unit', 30);
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('delivery_charges', 8, 2)->default(0);
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
