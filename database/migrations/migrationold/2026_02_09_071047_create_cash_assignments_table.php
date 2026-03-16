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
    Schema::create('cash_assignments', function (Blueprint $table) {
        $table->id();
        // Foreign key taake pata chale kis user ko cash mila
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->decimal('amount', 15, 2);
        $table->string('receiver_receipt')->nullable(); // Receipt image ka path
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_assignments');
    }
};
