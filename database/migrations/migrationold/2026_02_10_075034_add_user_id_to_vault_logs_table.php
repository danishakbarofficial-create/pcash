<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vault_logs', function (Blueprint $table) {
            // Check karein agar column nahi hai tabhi add karein
            if (!Schema::hasColumn('vault_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('vault_logs', 'type')) {
                $table->string('type')->nullable()->after('user_id'); 
            }
        });
    }

    public function down(): void
    {
        // Down ko khali chorr den taake refresh/rollback error na de
    }
};