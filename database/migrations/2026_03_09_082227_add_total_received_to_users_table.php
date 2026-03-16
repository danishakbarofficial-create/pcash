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
    Schema::table('users', function (Blueprint $table) {
        // Agar column pehle se nahi hai toh add karein
        if (!Schema::hasColumn('users', 'total_received')) {
            $table->decimal('total_received', 15, 2)->default(0)->after('cash_balance');
        }
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('total_received');
    });
}
};
