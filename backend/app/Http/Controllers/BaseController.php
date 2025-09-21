<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected string $model;
    protected string $entityName;
    protected ?string $formRequest = null;

    public function index()
    {
        $items = $this->model::paginate(10);

        return response()->json($items, 200);
    }

    public function show(string $id)
    {
        $itemFound = $this->model::find($id);

        if (!$itemFound) {
            throw new Exception($this->entityName . " não localizado.");
        }

        return response()->json($itemFound, 200);
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        $item = new $this->model();

        $item->fill($data);

        $item->save();

        return response()->json($item, 201);
    }

    public function update(Request $request, string $id)
    {
        $data = $this->validateRequest($request);

        $itemFound = $this->model::find($id);

        $itemFound->update($data);

        return response()->json($itemFound, 200);

    }

    public function destroy(string $id)
    {
        $removed = $this->model::destroy($id);

        if (!$removed) {
            throw new Exception($this->entityName . " não removido");
        }

        return response()->json(null, 204);
    }

    protected function validateRequest(Request $request)
    {
        if ($this->formRequest) {
            $formRequest = app($this->formRequest);

            return $formRequest->validated();
        }

        return $request->all();
    }
}
