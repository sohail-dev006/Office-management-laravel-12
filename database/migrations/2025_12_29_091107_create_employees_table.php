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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('designation');
            $table->string('password');
            $table->string('phone');
            $table->string('address');
            $table->enum('gender', ['male','female','other']);
            $table->string('job_type');
            $table->date('dob');
            $table->string('city');
            $table->decimal('salary', 10, 2);
            $table->enum('status', ['active','inactive'])->default('active');
            $table->integer('age');
            $table->date('join_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
