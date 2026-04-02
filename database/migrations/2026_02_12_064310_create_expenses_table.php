<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->string('category_id')->nullable(); 
        $table->date('expense_date')->nullable();
        $table->decimal('amount', 15, 2);
        $table->string('description');
        $table->string('receipt_photo')->nullable();
        $table->string('project_name')->nullable();
        $table->string('cost_center')->nullable();
        $table->string('status')->default('pending_manager');
        $table->text('rejection_reason')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};