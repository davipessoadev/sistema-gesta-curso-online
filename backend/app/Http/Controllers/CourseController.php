<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseFormRequest;
use App\Models\Course;

class CourseController extends BaseController
{
    protected string $model = Course::class;
    protected string $entityName = "curso";
    protected ?string $formRequest = CourseFormRequest::class;
}
