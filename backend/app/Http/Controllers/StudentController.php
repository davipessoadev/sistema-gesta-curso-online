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

        $items = Student::query()
            ->when($field && $value, function ($query) use ($field, $value) {
                $query->where($field, 'like', "%{$value}%");
            })
        ->paginate(10);

    return response()->json($items, 200);
    }

    public function show(string $id)
    {
        $itemFound = Student::find($id);

        if (!$itemFound) {
            throw new Exception("Aluno não localizado.", 404);
        }

        return response()->json($itemFound, 200);
    }

    public function store(StudentFormRequest $request)
    {
        $data = $request->validated();

        $item = new Student();

        $item->fill($data);

        $item->save();

        return response()->json($item, 201);
    }

    public function update(StudentFormRequest $request, string $id)
    {
        $data = $request->validated();

        $itemFound = Student::find($id);

        $itemFound->update($data);

        return response()->json($itemFound, 200);

    }

    public function destroy(string $id)
    {
        $removed = Student::destroy($id);

        if (!$removed) {
            throw new Exception("Aluno não removido", 404);
        }

        return response()->json(null, 204);
    }
}
