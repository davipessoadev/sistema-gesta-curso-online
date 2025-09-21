<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class CourseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        $rules = [
            "name" => "required|string|max:60",
            "description" => "nullable",
            "duration_hours" => "required|integer|min:1",
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['course_id'] = 'required|exists:courses,id';
        }

        return $rules;
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        if ($this->route('course')) {
            $data['course_id'] = $this->route('course');
        }

        return $data;
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O campo nome deve ser um texto',
            'description.string' => 'A descrição do curso deve ser um texto',
            'duration_hours.required' => 'O campo duração do curso é obrigatória',
            'duration_hours.integer' => 'O campo duração do curso deve ser um valor numerico',
            'duration_hours.min' => 'A duração do curso deve ser um número positivo',
            'course_id.exists' => 'O ID do curso deve ser existente',
        ];
    }

}
