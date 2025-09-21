<?php

namespace App\Http\Requests;

use App\Rules\CpfRule;
use Illuminate\Foundation\Http\FormRequest;

class FormStudentRequest extends FormRequest
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
        $studentId = $this->route()->parameter('student');

        return [
            "name" => "required|string",
            "email" => "required|email|unique:students,email,{$studentId}",
            "cpf" => ['required', 'string', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', new CpfRule]
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O campo nome deve ser um texto',
            'student_id.exists' => 'O ID deve ser de um curso existente',
        ];
    }
}
