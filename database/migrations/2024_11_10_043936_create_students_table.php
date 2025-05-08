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

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('nationality');
            $table->string('photo')->nullable();
            $table->date('admission_date');
            $table->string('current_class')->nullable();
            $table->foreignId('academic_year_id')->constrained();
            $table->string('education_level')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_relationship');
            $table->string('guardian_phone');
            $table->string('guardian_email')->nullable();
            $table->text('guardian_address');
            $table->string('guardian_occupation');
            $table->text('health_issues')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('emergency_contact');
            $table->string('status')->default('active');
            $table->json('additional_info')->nullable();
            $table->foreignId('class_room_id')->nullable()->constrained();
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
        Schema::dropIfExists('students');
    }
};
