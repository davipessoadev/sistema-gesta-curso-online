<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentFormRequest;
use App\Models\Student;

class StudentController extends BaseController
{
    protected string $model = Student::class;
    protected string $entityName = "aluno";
    protected ?string $formRequest = StudentFormRequest::class;
}
