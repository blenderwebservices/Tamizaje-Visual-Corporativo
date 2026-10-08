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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('screening_id')->nullable()->constrained('screenings')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('product_type', 50)->default('safety_glasses'); // safety_glasses, prescription_glasses, blue_light, accessories
            $table->string('frame_model')->nullable();
            $table->string('lens_type')->nullable(); // Antireflejante, Policarbonato de Seguridad, Blue Ray, Fotocromático
            $table->string('payment_method', 50)->nullable(); // payroll_deduction (Nómina), card, cash, transfer
            $table->string('status', 30)->default('completed'); // completed, in_process, delivered
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
