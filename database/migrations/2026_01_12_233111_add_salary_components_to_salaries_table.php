<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {

            // Earnings
            $table->decimal('basic_salary', 10, 2)->default(0)->after('absent_days');
            $table->decimal('house_allowance', 10, 2)->default(0);
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);

            // Deductions
            $table->decimal('advance_salary', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('other_deduction', 10, 2)->default(0);

            $table->decimal('total_deductions', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn([
                'basic_salary',
                'house_allowance',
                'medical_allowance',
                'transport_allowance',
                'other_allowance',
                'bonus',
                'advance_salary',
                'tax',
                'other_deduction',
                'total_deductions'
            ]);
        });
    }
};
