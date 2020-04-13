<?php

namespace App\Http\Requests\Staff;

use App\Types\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'min:3', 'max:190'],
            'email' => [
                'sometimes', 'required', 'string', 'min:3', 'max:190', 'email',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'roles' => ['sometimes', 'required', 'array', 'min:1'],
            'roles.*' => ['sometimes', 'required', 'integer', 'exists:roles,id'],
            'type' => ['sometimes', Rule::in(UserType::values())],
        ];
    }
}
