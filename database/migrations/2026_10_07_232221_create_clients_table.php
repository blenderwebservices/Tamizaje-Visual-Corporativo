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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('client_code')->nullable()->index();
            $table->string('subject_code')->nullable()->index(); // ID Sujeto from SpotVision (e.g. ENG7)
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->index();
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->date('birth_date')->nullable()->index();
            $table->unsignedSmallInteger('age')->nullable()->index();
            $table->string('gender', 10)->nullable(); // 'H', 'M', 'O'
            $table->boolean('terms_accepted')->default(false);
            $table->timestamp('terms_accepted_at')->nullable();
            $table->string('crm_stage')->default('prospect')->index(); // prospect, screened, purchased_onsite, requires_glasses_pending, pass_preventive
            $table->text('purchase_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
