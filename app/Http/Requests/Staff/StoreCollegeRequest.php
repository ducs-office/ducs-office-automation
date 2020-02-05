<?php

namespace App\Http\Requests\Staff;

use App\College;
use Illuminate\Foundation\Http\FormRequest;

class StoreCollegeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', College::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required','min:3','max:20','unique:colleges,code'],
            'name' => ['required','min:3','max:100','unique:colleges,name'],
            'principal_name' => ['required', 'min:3', 'max:190'],
            'principal_phones' => ['required', 'array', 'min:1', 'max:3'],
            'principal_phones.*' => ['nullable', 'numeric', 'digits:10'],
            'principal_emails' => ['required', 'array', 'min:1', 'max:3'],
            'principal_emails.*' => ['nullable','string', 'email'],
            'address' => ['required', 'min:10', 'max:250'],
            'website' => ['required', 'url'],
            'programmes' => ['required', 'array', 'min:1'],
            'programmes.*' => ['required', 'integer', 'exists:programmes,id']
        ];
    }
}
