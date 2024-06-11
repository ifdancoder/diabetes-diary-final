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
        Schema::create('stress_level_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('start_record')->constrained('records')->onDelete('cascade');
            $table->foreignId('end_record')->nullable()->default(null)->constrained('records')->onDelete('cascade');
            $table->foreignId('stress_level_type_id')->constrained('stress_level_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stress_level_sessions');
    }
};
