<?php

namespace App\Http\Requests\Staff;

use App\College;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCollegeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', College::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('colleges')->ignore($this->route('college')),
            ],
            'name' => [
                'sometimes', 'required', 'min:3', 'max:100',
                Rule::unique('colleges')->ignore($this->route('college')),
            ],
            'principal_name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'principal_phones' => ['sometimes', 'required', 'array', 'min:1', 'max:3'],
            'principal_phones.*' => ['nullable', 'numeric', 'digits:10'],
            'principal_emails' => ['sometimes', 'required', 'array', 'min:1', 'max:3'],
            'principal_emails.*' => ['nullable', 'string', 'email'],
            'address' => ['sometimes', 'required', 'min:10', 'max:250'],
            'website' => ['sometimes', 'required', 'url'],
            'programmes' => ['sometimes', 'required', 'array', 'min:1'],
            'programmes.*' => ['sometimes', 'required', 'integer', 'exists:programmes,id'],
        ];
    }
}
