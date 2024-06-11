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
        Schema::create('personal_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('log_in_out_notifications')->default(true);
            $table->boolean('reminder_notifications')->default(true);
            $table->boolean('notifications_from_social')->default(true);
            $table->foreignId('timezone_id')->nullable()->constrained('timezones');
            $table->boolean('show_datetime_type')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_settings');
    }
};
