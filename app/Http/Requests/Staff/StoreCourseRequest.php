<?php

namespace App\Http\Requests\Staff;

use App\Models\Course;
use App\Types\CourseType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            'code' => ['required', 'min:3', 'max:60', 'unique:courses'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', Rule::in(CourseType::values())],
            'date' => ['required', 'date', 'before_or_equal:now'],
            'attachments' => ['required', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:200', 'mimes:jpeg,jpg,png,pdf'],
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
