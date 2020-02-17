<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $types = implode(',', array_keys(config('options.courses.types')));

        return [
            'code' => [
                'sometimes', 'required', 'min:3', 'max:60',
                Rule::unique('courses')->ignore($this->route('course')),
            ],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'type' => ['sometimes', 'required', 'in:' . $types],
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
}
