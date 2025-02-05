<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sugar_levels', function (Blueprint $table) {
            $table->decimal('val', 7, 5)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sugar_levels', function (Blueprint $table) {
            $table->decimal('val', 5, 3)->change();
        });
    }
};
