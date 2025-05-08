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

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('academic_year_id')->constrained();
            $table->text('description')->nullable();
            $table->integer('credits');
            $table->integer('hours_per_week');
            $table->string('course_type');
            $table->string('education_level');
            $table->string('semester');
            $table->integer('max_students');
            $table->json('prerequisites')->nullable();
            $table->text('syllabus')->nullable();
            $table->text('objectives')->nullable();
            $table->string('assessment_method');
            $table->string('status')->default('active');
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
        Schema::dropIfExists('courses');
    }
};
