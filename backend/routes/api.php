<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/courses/{course}/students', [CourseController::class, 'getStudents']);
Route::apiResource('/courses', CourseController::class);

Route::get('/students/{student}', [StudentController::class, 'show']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students', [StudentController::class, 'index']);

Route::post('/enrollments', [EnrollmentController::class, 'store']);
Route::patch('/enrollments/{enrollment}/progress', [EnrollmentController::class, 'update']);
