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
            // Добавляем новую колонку с возможностью быть NULL
            $table->string('certificate_image')->nullable()->after('contract_document'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Удаляем колонку, если нужно откатить миграцию
            $table->dropColumn('certificate_image');
        });
    }
};