<?php

namespace App\Http\Requests\Staff;

use App\Models\PhdCourse;
use App\Types\PrePhdCourseType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdatePhdCourseRequest extends FormRequest
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
        $course = $this->route('course');
        return [
            'code' => ['sometimes', 'required', 'min:3', 'max:60', Rule::unique('phd_courses')->ignore($course)],
            'name' => ['sometimes', 'required', 'min:3', 'max:190'],
            'type' => ['sometimes', 'required', Rule::in(PrePhdCourseType::values())],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = redirect()->back()
            ->withInput($this->input() + ['course_id' => $this->route('course')->id])
            ->withErrors($validator->errors()->messages(), 'update');

        throw new ValidationException($validator, $response);
    }
}
