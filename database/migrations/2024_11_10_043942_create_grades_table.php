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

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained();
            $table->foreignId('course_id')->nullable()->constrained();
            $table->foreignId('course_enrollment_id')->nullable()->constrained();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('trimester', [1, 2, 3]);
            $table->string('grade_type'); // TJ1, TJ2, TJ3, TJ4, etc.
            $table->decimal('score', 5, 2); // Score réel
            $table->decimal('max_score', 5, 2); // Score maximum possible (ex: /20, /10)
            $table->text('comment')->nullable();
            $table->date('evaluation_date')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('teachers')->onDelete('cascade');
            $table->foreignId('recorder_id')->nullable();
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
        Schema::dropIfExists('grades');
    }
};
