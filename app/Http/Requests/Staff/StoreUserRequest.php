<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'min:3', 'max:255'],
            'first_name' => ['required_without:name', 'string', 'min:3', 'max:190'],
            'last_name' => ['required_without:name', 'string', 'max:190'],
            'email' => ['required', 'string', 'min:3', 'max:190', 'email', 'unique:users'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'integer', 'exists:roles,id'],
            'category' => ['required', Rule::in(UserCategory::values())],
            'is_supervisor' => ['sometimes', 'boolean'],
        ];
    }
}
