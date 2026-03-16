<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Safe check: Agar column nahi hai tabhi add kare
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->default('staff'); 
        }
        if (!Schema::hasColumn('users', 'project_name')) {
            $table->string('project_name')->nullable();
        }
        if (!Schema::hasColumn('users', 'cost_center')) {
            $table->string('cost_center')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
