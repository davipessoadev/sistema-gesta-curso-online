<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseFormRequest;
use App\Models\Course;
use Exception;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('students')->paginate(10);

        return response()->json($courses, 200);
    }

    public function show(string $id)
    {
        $courseFound = Course::find($id);

        if (!$courseFound) {
            throw new Exception("Curso não localizado.", 404);
        }

        return response()->json($courseFound, 200);
    }

    public function store(CourseFormRequest $request)
    {
        $data = $request->validated();

        $course = new Course();

        $course->fill($data);

        $course->save();

        return response()->json($course, 201);
    }

    public function update(CourseFormRequest $request, string $id)
    {
        $data = $request->validated();

        $courseFound = Course::find($id);

        $courseFound->update($data);

        return response()->json($courseFound, 200);

    }

    public function destroy(string $id)
    {
        $removedCourse = Course::destroy($id);

        if (!$removedCourse) {
            throw new Exception("Curso não removido.", 404);
        }

        return response()->json(null, 204);
    }

    public function getStudents(string $id) {
        $courseWithStudents = Course::find($id)->students;

        if (!$courseWithStudents) {
            throw new Exception("Curso não encontrado");
        }

        return response()->json($courseWithStudents, 200);
    }
}
