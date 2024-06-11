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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->datetime('datetime');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('timezone_id')->constrained('timezones')->onDelete('cascade');
            $table->timestamps();
            $table->unique(array('datetime', 'user_id'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
