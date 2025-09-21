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

        try {
            $course = new Course();

            $course->fill($data);

            $course->save();

            return response()->json($course, 201);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Erro ao cadastrar o curso'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = Course::findOrFail($id);

            return response()->json($course, 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Erro ao buscar o curso'
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormCourseRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $course = Course::find($id);

            $course->update($data);

            return response()->json($course, 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Erro ao atualizar o curso'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $removed = Course::destroy($id);

            if (!$removed) {
                throw new Exception();
            }

            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Erro ao remover o curso'
            ], 400);
        }
    }
}
