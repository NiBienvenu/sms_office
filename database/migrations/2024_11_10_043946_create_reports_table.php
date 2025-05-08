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
        Schema::disableForeignKeyConstraints();

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->foreignId('academic_year_id')->constrained();
            $table->string('semester')->nullable();
            $table->json('parameters')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->string('file_path')->nullable();
            $table->string('status')->default('generated');
            $table->foreignId('generator_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
