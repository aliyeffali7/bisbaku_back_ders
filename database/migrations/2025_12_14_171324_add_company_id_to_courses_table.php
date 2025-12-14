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
        Schema::table('courses', function (Blueprint $table) {
            // ✅ Добавляем foreignId company_id с nullable
            $table->foreignId('company_id')
                ->nullable() 
                ->after('contract_document') // Или после любого другого столбца
                ->constrained('companies') // Предполагаем, что таблица компаний называется 'companies'
                ->onDelete('set null'); // При удалении компании, company_id в курсах станет null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};