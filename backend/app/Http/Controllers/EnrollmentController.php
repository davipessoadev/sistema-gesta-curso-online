<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentFormRequest;
use App\Models\Enrollment;

class EnrollmentController extends BaseController
{
    protected string $model = Enrollment::class;
    protected string $entityName = "matricula";
    protected ?string $formRequest = EnrollmentFormRequest::class;
}
