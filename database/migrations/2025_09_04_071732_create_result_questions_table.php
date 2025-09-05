<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id');
            $table->string('title');
            $table->string('correct_answer');
            $table->string('user_answer');
            $table->boolean('status')->default(false); // правильно или нет
            $table->timestamps();

            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_questions');
    }
};
