<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_shipping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('shipping_method');
            $table->string('tracking_number')->nullable();
            $table->enum('status', ['pending', 'shipped', 'in_transit', 'delivered'])->default('pending');
            $table->timestamp('shipping_at')->nullable();
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('tbl_order')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_shipping');
    }
};