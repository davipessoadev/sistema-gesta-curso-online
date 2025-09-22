<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentFormRequest;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{

    public function store(EnrollmentFormRequest $request)
    {
        $data = $request->validated();

        $course = new Enrollment();

        $course->fill($data);

        $course->save();

        return response()->json($course, 201);
    }

    public function update(EnrollmentFormRequest $request, Enrollment $enrollment)
    {
        $data = $request->validated();

        $enrollment->update($data);

        return response()->json($enrollment, 200);
    }
}
