<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ApplyController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ResultQuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserUnlockExamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/courses', [CourseController::class,'index']);
Route::get('/courses/{id}', [CourseController::class,'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/applies', [ApplyController::class, 'index']);
    Route::post('/applies', [ApplyController::class, 'store']);
    Route::get('/applies/{id}', [ApplyController::class, 'show']);

    Route::get('/user', [UserController::class, 'me']);

    Route::get('/unlock-exams', [UserUnlockExamController::class, 'index']);
    Route::get('/unlock-exams/{id}', [UserUnlockExamController::class, 'show']);

    Route::get('/exams', [ExamController::class, 'index']);
    Route::get('/exams/{id}', [ExamController::class, 'show']);

    Route::get('/questions', [QuestionController::class, 'index']);
    Route::get('/questions/{id}', [QuestionController::class, 'show']);

    Route::get('/answers', [AnswerController::class, 'index']);
    Route::get('/answers/{id}', [AnswerController::class, 'show']);

    Route::get('/results', [ResultController::class, 'index']);
    Route::get('/results/{id}', [ResultController::class, 'show']);
    Route::post('/results', [ResultController::class, 'store']);

    Route::get('/result-questions', [ResultQuestionController::class, 'index']);
    Route::get('/result-questions/{id}', [ResultQuestionController::class, 'show']);
    Route::post('/result-questions', [ResultQuestionController::class, 'store']);

    Route::get('/certificates', [CertificateController::class, 'index']);
    Route::get('/certificates/{id}', [CertificateController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::post('/exams-admin', [ExamController::class, 'store']);
    Route::put('/exams-admin/{id}', [ExamController::class, 'update']);
    Route::delete('/exams-admin/{id}', [ExamController::class, 'destroy']);

    Route::post('/questions-admin', [QuestionController::class, 'store']);
    Route::put('/questions-admin/{id}', [QuestionController::class, 'update']);
    Route::delete('/questions-admin/{id}', [QuestionController::class, 'destroy']);

    Route::post('/answers-admin', [AnswerController::class, 'store']);
    Route::put('/answers-admin/{id}', [AnswerController::class, 'update']);
    Route::delete('/answers-admin/{id}', [AnswerController::class, 'destroy']);

    Route::post('/unlock-exams', [UserUnlockExamController::class, 'store']);
    Route::put('/unlock-exams/{id}', [UserUnlockExamController::class, 'update']);
    Route::delete('/unlock-exams/{id}', [UserUnlockExamController::class, 'destroy']);

    Route::put('/results/{id}', [ResultController::class, 'update']);
    Route::delete('/results/{id}', [ResultController::class, 'destroy']);

        Route::put('/result-questions-admin/{id}', [ResultQuestionController::class, 'update']);
    Route::delete('/result-questions-admin/{id}', [ResultQuestionController::class, 'destroy']);

    Route::put('/course-admin/{id}', [CourseController::class,'update']);
    Route::delete('/course-admin/{id}', [CourseController::class,'destroy']);
    Route::post('/courses-admin', [CourseController::class,'store']);

    Route::put('/applies-admin/{id}', [ApplyController::class, 'update']);
    Route::delete('/applies-admin/{id}', [ApplyController::class, 'destroy']);

    Route::post('/certificates-admin', [CertificateController::class, 'store']);
    Route::put('/certificates-admin/{id}', [CertificateController::class, 'update']);
    Route::delete('/certificates-admin/{id}', [CertificateController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'allUsers']);
    Route::get('/user/{id}', [UserController::class, 'findById']);
});