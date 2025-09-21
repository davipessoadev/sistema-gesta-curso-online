<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseFormRequest;
use App\Models\Course;
use Exception;

class CourseController extends BaseController
{
    protected string $model = Course::class;
    protected string $entityName = "curso";
    protected ?string $formRequest = CourseFormRequest::class;

    public function getStudents(string $id) {
        $courseWithStudents = $this->model::find($id);

        if (!$courseWithStudents) {
            throw new Exception("Curso não encontrado");
        }

        $courseWithStudents->students;

        return response()->json($courseWithStudents, 200);
    }
}
