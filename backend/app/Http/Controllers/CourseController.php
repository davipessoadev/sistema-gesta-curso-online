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

    public function show(Course $course)
    {
        return response()->json($course, 200);
    }

    public function store(CourseFormRequest $request)
    {
        $data = $request->validated();

        $course = new Course();

        $course->fill($data);

        $course->save();

        return response()->json($course, 201);
    }

    public function update(CourseFormRequest $request, Course $course)
    {
        $data = $request->validated();

        $course->update($data);

        return response()->json($course, 200);

    }

    public function destroy(string $id)
    {
        $removedCourse = Course::destroy($id);

        if (!$removedCourse) {
            throw new Exception("Curso não removido.", 404);
        }

        return response()->json(null, 204);
    }

    public function getStudents(Course $course) {
        $courseWithStudents = $course->students;

        return response()->json($courseWithStudents, 200);
    }
}
