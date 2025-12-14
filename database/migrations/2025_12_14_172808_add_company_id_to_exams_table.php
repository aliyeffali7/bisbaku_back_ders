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
        Schema::table('exams', function (Blueprint $table) {
            // ✅ Добавляем foreignId company_id с nullable
            $table->foreignId('company_id')
                ->nullable() 
                ->after('duration_minutes') // Размещаем после duration_minutes
                ->constrained('companies') // Связываем с таблицей 'companies'
                ->onDelete('set null'); // При удалении компании, company_id в экзаменах станет null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};