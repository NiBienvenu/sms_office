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

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('nationality');
            $table->string('photo')->nullable();
            $table->date('joining_date');
            $table->string('contract_type');
            $table->string('employment_status')->default('active');
            $table->string('qualification');
            $table->string('specialization');
            $table->integer('experience_years');
            $table->text('previous_employment')->nullable();
            $table->foreignId('department_id')->constrained();
            $table->string('position');
            $table->string('salary_grade');
            $table->string('bank_account')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('social_security_number')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->json('additional_info')->nullable();
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
        Schema::dropIfExists('teachers');
    }
};
