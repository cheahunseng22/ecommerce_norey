<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bakong_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('hash')->unique();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('KHR');
            $table->string('city')->nullable();
            $table->string('qr_payload');  // raw QR data
            $table->enum('status', ['pending', 'paid', 'expired'])->default('pending');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bakong_transactions');
    }
};
