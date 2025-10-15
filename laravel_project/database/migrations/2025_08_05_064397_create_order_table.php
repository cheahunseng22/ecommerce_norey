<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('order_code')->unique();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('payment_method');
            $table->text('shipping_address');
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('delivery_at')->nullable();
            $table->timestamps();
            
            // Make sure this references the correct table name
       $table->foreign('customer_id')
      ->references('id')
      ->on('customers')  // ← PLURAL
      ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_order');
    }
};