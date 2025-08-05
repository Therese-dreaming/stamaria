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
        Schema::create('booking_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('requirement_name'); // e.g., "Valid ID", "Birth Certificate"
            $table->string('file_path'); // Path to uploaded file
            $table->string('original_filename'); // Original filename
            $table->string('file_type')->nullable(); // MIME type
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->text('notes')->nullable(); // Any additional notes
            $table->timestamps();
            
            $table->index(['booking_id', 'requirement_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_requirements');
    }
};
