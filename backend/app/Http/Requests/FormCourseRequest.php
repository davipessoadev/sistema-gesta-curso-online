<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class FormCourseRequest extends FormRequest
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
            "duration_hours" => "required|integer",
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
            'name.required' => 'O nome do curso é obrigatório',
            'name.max' => 'O nome não deve conter mais que 60 caracteres',
            'name.string' => 'O nome do curso deve ser um texto',
            'description.string' => 'A descrição do curso deve ser um texto',
            'duration_hours.required' => 'A duração do curso é obrigatória',
            'duration_hours.integer' => 'A duração do curso deve ser um valor numerico',
            'duration_hours.min' => 'A duração do curso deve ser um número positivo',
            'course_id.required' => 'O ID do curso é obrigatório',
            'course_id.exists' => 'O curso deve ser válido',
        ];
    }

}
