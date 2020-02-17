<?php

namespace App\Http\Requests\Staff;

use App\Course;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Course::class);
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
            'code' => ['required', 'min:3', 'max:60', 'unique:courses'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', 'in:' . $types],
            'date' => ['required', 'date', 'before_or_equal:now'],
            'attachments' => ['required', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:200', 'mimes:jpeg,jpg,png,pdf'],
        ];
    }

    public function storeAttachments()
    {
        return array_map(function ($attachedFile) {
            return [
                'path' => $attachedFile->store('/course_attachments'),
                'original_name' => $attachedFile->getClientOriginalName(),
            ];
        }, $this->file('attachments', []));
    }
}
