<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/courses/{id}/students', [CourseController::class, 'getStudents']);
Route::apiResource('/courses', CourseController::class);

Route::get('/students/{id}', [StudentController::class, 'show']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students', [StudentController::class, 'index']);

Route::post('/enrollments', [EnrollmentController::class, 'store']);
Route::patch('/enrollments/{id}/progress', [EnrollmentController::class, 'update']);
