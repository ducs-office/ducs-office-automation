<?php

namespace App\Http\Requests\Staff;

use App\Types\UserCategory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

    public function prepareForValidation()
    {
        $attributes = [];
        if ($this->has('is_supervisor')) {
            $attributes['is_supervisor'] = $this->filled('is_supervisor');
        }

        if ($this->has('is_cosupervisor')) {
            $attributes['is_cosupervisor'] = $this->filled('is_cosupervisor');
        }

        return $this->merge($attributes);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('user');
        return [
            'first_name' => ['sometimes', 'required', 'string', 'min:3', 'max:190'],
            'last_name' => ['sometimes', 'required', 'string', 'max:190'],
            'email' => [
                'sometimes', 'required', 'string', 'min:3', 'max:190',
                'email', Rule::unique('users')->ignore($user),
            ],
            'roles' => [ // not assigning any role to external, even if sent in the request
                'sometimes',
                'required_unless:category,' . UserCategory::EXTERNAL . ',' . UserCategory::COLLEGE_TEACHER,
                'exclude_if:category,' . UserCategory::EXTERNAL,
                'array', 'min:1',
            ],
            'roles.*' => ['sometimes', 'required', 'integer', 'exists:roles,id'],
            'category' => ['sometimes', 'required', Rule::in(UserCategory::values())],
            'designation' => ['sometimes', 'exclude_unless:category,' . UserCategory::EXTERNAL],
            'affiliation' => ['sometimes', 'exclude_unless:category,' . UserCategory::EXTERNAL],
            'address' => ['sometimes', 'exclude_unless:category,' . UserCategory::EXTERNAL],
            'is_supervisor' => [
                'sometimes', 'boolean', // only these can be supervisors
                'exclude_unless:category,' . implode(',', [
                    UserCategory::COLLEGE_TEACHER,
                    UserCategory::FACULTY_TEACHER,
                ]),
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->isSupervisor() && $value === false) {
                        $fail('A supervisor can not be removed from being a supervisor');
                    }
                },
            ],
            'is_cosupervisor' => [
                'sometimes', 'boolean', // only these can be cosupervisors
                'exclude_if:is_supervisor,true',
                'exclude_unless:category,' . implode(',', [
                    UserCategory::COLLEGE_TEACHER,
                    UserCategory::FACULTY_TEACHER,
                    UserCategory::EXTERNAL,
                ]),
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->isSupervisor() && $value === true) {
                        $fail('A supervisor can not be made a co-supervisor');
                    }

                    if ($user->isCosupervisor() && $value === false) {
                        $fail('A co-supervisor can not be removed from being a co-supervisor');
                    }
                },
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = redirect()->back()
            ->withInput($this->input() + ['user_id' => $this->route('user')->id]) // sending user id to show modal again
            ->withErrors($validator->errors()->messages(), 'update');

        throw new ValidationException($validator, $response);
    }
}
