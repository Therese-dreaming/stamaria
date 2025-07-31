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
        Schema::create('priests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable(); // e.g., "Parish Priest", "Assistant Priest"
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('image')->nullable(); // Profile image path
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('ordination_date')->nullable();
            $table->date('assignment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priests');
    }
};
