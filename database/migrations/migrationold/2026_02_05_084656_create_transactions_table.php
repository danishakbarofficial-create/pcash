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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('description'); // Kharch ki wajah (e.g., Office Tea)
        $table->decimal('amount', 10, 2); // Paison ki raqam
        $table->enum('type', ['in', 'out']); // 'in' = paisa aaya, 'out' = kharch hua
        $table->date('transaction_date'); // Kis din kharch hua
        $table->timestamps(); // Created_at aur Updated_at (Laravel khud manage karta hai)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
