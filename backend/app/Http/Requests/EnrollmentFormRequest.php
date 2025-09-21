<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentFormRequest extends FormRequest
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
        $rules = [];

        if ($this->isMethod('POST')) {
            $rules["student_id"] = "required|integer|exists:students,id";
            $rules["course_id"] = "required|integer|exists:courses,id";
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['progress_percentage'] = 'required|integer|min:0|max:100';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'O ID do curso é obrigatório',
            'student_id.required' => 'O ID do aluno é obrigatório',
            'course_id.exists' => 'O ID do curso deve ser existente',
            'student_id.exists' => 'O ID do aluno deve ser existente',
            'progress_percentage.required' => 'A porcentagem do curso é obrigatória',
            'progress_percentage.min' => 'A porcentagem do curso deve ser pelo menos 0',
            'progress_percentage.max' => 'A porcentagem do curso não pode ser superior a 100',
        ];
    }
}
