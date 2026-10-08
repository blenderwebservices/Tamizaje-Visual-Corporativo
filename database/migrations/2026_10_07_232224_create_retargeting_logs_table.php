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
        Schema::create('retargeting_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('stage', 20)->index(); // 'day_3', 'day_15', 'day_90'
            $table->string('channel', 20)->default('whatsapp'); // 'whatsapp', 'email'
            $table->string('status', 20)->default('pending')->index(); // 'pending', 'sent', 'converted'
            $table->date('scheduled_for')->index();
            $table->timestamp('sent_at')->nullable();
            $table->text('message_content')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retargeting_logs');
    }
};
