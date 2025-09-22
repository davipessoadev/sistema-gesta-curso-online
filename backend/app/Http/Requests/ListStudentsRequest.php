<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListStudentsRequest extends FormRequest
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
        return [
            'field' => 'nullable|string|in:name,email,cpf',
            'value' => 'nullable|string|max:255',
        ];
    }

    public function all($keys = null)
    {
        return $this->query();
    }
}
