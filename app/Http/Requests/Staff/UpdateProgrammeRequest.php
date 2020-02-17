<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgrammeRequest extends FormRequest
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
        $types = implode(',', array_keys(config('options.programmes.types')));

        return [
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('programmes')->ignore($this->programme),
            ],
            'wef' => ['sometimes', 'required', 'date'],
            'type' => ['sometimes', 'required', 'in:' . $types],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
        ];
    }
}
