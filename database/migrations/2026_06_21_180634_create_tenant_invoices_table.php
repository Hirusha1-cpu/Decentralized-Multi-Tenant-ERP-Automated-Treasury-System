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
        Schema::create('tenant_invoices', function (Blueprint $table) {
        $table->id();
        $table->string('tenant_id');
        $table->unsignedBigInteger('amount_usd'); // ඩොලර් වටිනාකම (උදා: 500)
        $table->string('worker_wallet_address');  // සේවකයාගේ MetaMask Address එක
        $table->string('blockchain_tx_hash')->nullable(); // Receipt ID එක
        $table->enum('status', ['pending', 'paid'])->default('pending'); // Status එක
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_invoices');
    }
};
