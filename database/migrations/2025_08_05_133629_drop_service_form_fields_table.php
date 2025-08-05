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
        Schema::dropIfExists('service_form_fields');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('service_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('field_name');
            $table->string('label');
            $table->string('field_type');
            $table->text('options')->nullable();
            $table->boolean('required')->default(false);
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->string('validation_rules')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_conditional')->default(false);
            $table->string('condition_field')->nullable();
            $table->string('condition_value')->nullable();
            $table->timestamps();
            
            $table->index(['service_id', 'order']);
        });
    }
};
