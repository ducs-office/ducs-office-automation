<?php

namespace App\Http\Requests\Staff;

use App\Types\CourseType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('course'));
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
                Rule::unique('courses')->ignore($this->route('course')),
            ],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'type' => ['sometimes', 'required', Rule::in(CourseType::values())],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,jpg,png,pdf', 'max:200'],
        ];
    }

    public function storeAttachments()
    {
        return array_map(static function ($attachedFile) {
            return [
                'path' => $attachedFile->store('/course_attachments'),
                'original_name' => $attachedFile->getClientOriginalName(),
            ];
        }, $this->file('attachments', []));
    }

    protected function failedValidation(Validator $validator)
    {
        $response = redirect()->back()
            ->withInput($this->input() + ['course_id' => $this->route('course')->id])
            ->withErrors($validator->errors()->messages(), 'update');

        throw new ValidationException($validator, $response);
    }
}
