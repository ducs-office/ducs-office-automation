<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

    public function prepareForValidation()
    {
        $this->merge([
            'is_supervisor' => $this->filled('is_supervisor'),
            'is_cosupervisor' => $this->filled('is_cosupervisor'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:190'],
            'last_name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'string', 'min:3', 'max:190', 'email', 'unique:users'],
            'roles' => [ // not assigning any role to external, even if sent in the request
                'required_unless:category,' . UserCategory::EXTERNAL,
                'exclude_if:category,' . UserCategory::EXTERNAL,
                'array', 'min:1',
            ],
            'roles.*' => ['required', 'integer', 'exists:roles,id'],
            'category' => ['required', Rule::in(UserCategory::values())],
            'designation' => ['sometimes', 'exclude_unless:category,' . UserCategory::EXTERNAL],
            'affiliation' => ['sometimes', 'exclude_unless:category,' . UserCategory::EXTERNAL],
            'address' => ['sometimes', 'exclude_unless:category,' . UserCategory::EXTERNAL],
            'is_supervisor' => [
                'sometimes', 'boolean', // only these can be supervisors
                'exclude_unless:category,' . implode(',', [
                    UserCategory::COLLEGE_TEACHER,
                    UserCategory::FACULTY_TEACHER,
                ]),
            ],
            'is_cosupervisor' => [
                'sometimes', 'boolean', // only these can be cosupervisors
                'exclude_if:is_supervisor,true',
                'exclude_unless:category,' . implode(',', [
                    UserCategory::COLLEGE_TEACHER,
                    UserCategory::FACULTY_TEACHER,
                    UserCategory::EXTERNAL,
                ]),
            ],
        ];
    }
}
