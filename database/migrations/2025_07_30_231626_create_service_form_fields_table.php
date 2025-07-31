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
        Schema::create('service_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // e.g., 'bride_name', 'groom_name'
            $table->string('label'); // e.g., 'Bride Name', 'Groom Name'
            $table->string('field_type'); // 'text', 'email', 'tel', 'date', 'time', 'textarea', 'select', 'checkbox', 'radio'
            $table->text('options')->nullable(); // JSON for select/radio options
            $table->boolean('required')->default(false);
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->string('validation_rules')->nullable(); // Laravel validation rules
            $table->integer('order')->default(0); // For field ordering
            $table->boolean('is_conditional')->default(false);
            $table->string('condition_field')->nullable(); // Field this depends on
            $table->string('condition_value')->nullable(); // Value that triggers this field
            $table->timestamps();
            
            $table->index(['service_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_form_fields');
    }
};
