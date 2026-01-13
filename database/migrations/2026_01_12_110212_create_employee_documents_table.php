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
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            $table->string('title'); 
            $table->enum('type', [
                'cv',
                'cnic',
                'offer_letter',
                'contract',
                'degree',
                'other'
            ]);

            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
