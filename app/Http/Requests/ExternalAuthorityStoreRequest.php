<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalAuthorityStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:external_authorities,email',
            'designation' => 'required|string|max:191',
            'affiliation' => 'required|string|max:191',
            'phone' => 'sometimes|nullable|digits:10',
        ];
    }
}
