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
    Schema::table('transactions', function (Blueprint $table) {
        // Category column add karein (nullable rakhein taake purana data na phanse)
        $table->string('category')->nullable()->after('description');
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn('category');
    });
}
};
