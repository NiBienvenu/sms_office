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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable();
            $table->foreignId('class_room_id')->nullable();
            $table->foreignId('academic_year_id')->nullable();
            $table->tinyInteger('trimester')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->string('status')->default('draft');
            $table->string('average')->nullable();
            $table->integer('rank')->nullable();
            $table->text('teacher_comments')->nullable();
            $table->text('principal_comments')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
