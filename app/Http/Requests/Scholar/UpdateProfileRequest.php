<?php

namespace App\Http\Requests\Scholar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $scholar = $this->user();

        return [
            'phone_no' => [Rule::requiredIf($scholar->phone_no != null)],
            'address' => [Rule::requiredIf($scholar->address != null)],
            'category' => [Rule::requiredIf($scholar->category != null)],
            'admission_mode' => [Rule::requiredIf($scholar->admission_mode != null)],
            'profile_picture' => ['nullable', 'image'],
            'research_area' => [Rule::requiredIf($scholar->research_area != null)],
            'enrollment_date' => ['nullable', 'date', 'before:today'],

            'education' => ['required', 'array', 'max: 4', 'min:1'],
            'education.*' => ['required', 'array', 'size:4'],
            'education.*.degree' => ['required', 'integer'],
            'education.*.subject' => ['required', 'integer'],
            'education.*.institute' => ['required', 'integer'],
            'education.*.year' => ['required', 'string', 'size:4'],

            'typedSubjects' => ['sometimes', 'required', 'array', 'max:4'],
            'typedDegrees' => ['sometimes', 'required', 'array', 'max:4'],
            'typedInstitutes' => ['sometimes', 'required', 'array', 'max:4'],
        ];
    }
}
