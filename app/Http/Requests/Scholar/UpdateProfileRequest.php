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
        // dd($this->subject);
        $scholar = $this->user();

        return [
            'phone_no' => [Rule::requiredIf($scholar->phone_no != null)],
            'address' => [Rule::requiredIf($scholar->address != null)],
            'category' => [Rule::requiredIf($scholar->category != null)],
            'admission_via' => [Rule::requiredIf($scholar->admission_via != null)],
            'profile_picture' => ['nullable', 'image'],
            'research_area' => [Rule::requiredIf($scholar->research_area != null)],
            'supervisor_profile_id' => ['sometimes', 'required', 'exists:supervisor_profiles,id'],
            'enrollment_date' => ['nullable', 'date', 'before:today'],

            'advisory_committee' => ['required', 'array', 'max: 4'],
            'advisory_committee.*' => ['required', 'array', 'size:4'],
            'advisory_committee.*.title' => ['required', 'string'],
            'advisory_committee.*.name' => ['required', 'string'],
            'advisory_committee.*.designation' => ['required', 'string'],
            'advisory_committee.*.affiliation' => ['required', 'string'],

            'co_supervisors' => ['sometimes', 'array', 'max:2'],
            'co_supervisors.*' => ['sometimes', 'required', 'integer', 'exists:cosupervisors,id'],

            'education' => ['required', 'array', 'max: 4', 'min:1'],
            'education.*' => ['required', 'array', 'size:4'],
            'education.*.degree' => ['required', 'string'],
            'education.*.subject' => ['required', 'string'],
            'education.*.institute' => ['required', 'string'],
            'education.*.year' => ['required', 'string'],

            'subject' => ['sometimes', 'required', 'array', 'max:4'],
        ];
    }
}
