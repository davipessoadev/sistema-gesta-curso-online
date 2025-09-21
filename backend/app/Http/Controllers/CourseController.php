<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCourseRequest;
use App\Models\Course;
use Exception;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::simplePaginate(10);

        return response()->json($courses, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormCourseRequest $request)
    {
        $data = $request->validated();

        $course = new Course();

        $course->fill($data);

        $course->save();

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::findOrFail($id);

        if (!$course) {
            throw new Exception("Erro ao localizar o curso");
        }

        return response()->json($course, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormCourseRequest $request, string $id)
    {
        $data = $request->validated();

        $course = Course::find($id);

        $course->update($data);

        return response()->json($course, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $removed = Course::destroy($id);

        if (!$removed) {
            throw new Exception("Erro ao remover o curso");
        }

        return response()->json(null, 204);
    }
}
