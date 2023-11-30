<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
        $countRoles = Role::all()->count();

        return [
            'name' => 'required|string',
            'surname' => 'string',
            'patronymic' => 'string',
            'login' => 'required|string|unique:users',
            'password' => 'required|string',
            'photo_file' => 'image',
            'role_id' => "required|integer|min:1|max:$countRoles"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            "error" => [
                "code" => 422,
                "message" => "Validation error",
                "errors" => $validator->errors()
            ]
        ], 422));
    }
}
