<?php

namespace App\Http\Requests\Staff;

use App\Models\PhdCourse;
use App\Types\PrePhdCourseType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePhdCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', PhdCourse::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', 'min:3', 'max:60', 'unique:phd_courses'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', Rule::in(PrePhdCourseType::values())],
        ];
    }
}
