<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListStudentsRequest;
use App\Http\Requests\StudentFormRequest;
use App\Models\Student;
use Exception;

class StudentController extends Controller
{

    public function index(ListStudentsRequest $request)
    {
        $field = $request->validated('field');
        $value = $request->validated('value');

        $students = Student::query()
            ->when($field && $value, function ($query) use ($field, $value) {
                $query->where($field, 'like', "%{$value}%");
            })
        ->paginate(10);

    return response()->json($students, 200);
    }

    public function show(Student $student)
    {
        return response()->json($student, 200);
    }

    public function store(StudentFormRequest $request)
    {
        $data = $request->validated();

        $student = new Student();

        $student->fill($data);

        $student->save();

        return response()->json($student, 201);
    }

    public function update(StudentFormRequest $request, Student $student)
    {
        $data = $request->validated();

        $student->update($data);

        return response()->json($student, 200);

    }

    public function destroy(string $id)
    {
        $removedStudent = Student::destroy($id);

        if (!$removedStudent) {
            throw new Exception("Aluno não removido", 404);
        }

        return response()->json(null, 204);
    }
}
