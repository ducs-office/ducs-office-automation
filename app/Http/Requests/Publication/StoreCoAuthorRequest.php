<?php

namespace App\Http\Requests\Publication;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreCoAuthorRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'noc' => ['nullable', 'file', 'max:200', 'mimeTypes:application/pdf, image/*'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = redirect()->back()
            ->withInput($this->input() + ['publication_id' => $this->route('publication')->id])
            ->withErrors($validator->errors()->messages(), 'createCoAuthor');
        throw new ValidationException($validator, $response);
    }
}
