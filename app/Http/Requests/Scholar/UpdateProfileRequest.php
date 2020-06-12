<?php

namespace App\Http\Requests\Scholar;

use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Types\AdmissionMode;
use App\Types\EducationInfo;
use App\Types\FundingType;
use App\Types\Gender;
use App\Types\ReservationCategory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            'gender' => [
                'sometimes', Rule::requiredIf($scholar->gender != null),
                Rule::in(Gender::values()),
            ],
            'phone' => ['sometimes', Rule::requiredIf($scholar->phone != null), 'digits:10', 'string'],
            'address' => ['sometimes', Rule::requiredIf($scholar->address != null), 'string'],
            'category' => [
                'sometimes', Rule::requiredIf($scholar->category != null),
                Rule::in(ReservationCategory::values()),
            ],
            'admission_mode' => [
                'sometimes', Rule::requiredIf($scholar->category != null),
                Rule::in(AdmissionMode::values()),
            ],
            'funding' => [
                'sometimes', Rule::requiredIf($scholar->funding != null),
                Rule::in(FundingType::values()),
            ],
            'avatar' => ['nullable', 'image'],
            'research_area' => ['sometimes', Rule::requiredIf($scholar->research_area != null)],
            'registration_date' => ['sometimes', Rule::requiredIf($scholar->registration_date != null), 'before:today'],
            'enrolment_id' => ['sometimes', Rule::requiredIf($scholar->enrolment_id != null), 'string', 'max:30'],
            'education_details' => ['required', 'array', 'max: 4', 'min:1'],
            'education_details.*' => ['required', 'array', 'size:4'],
            'education_details.*.degree' => ['required', 'string', 'max:190', 'min:2'],
            'education_details.*.subject' => ['required', 'string', 'max:190', 'min:3'],
            'education_details.*.institute' => ['required', 'string', 'max:190', 'min:2'],
            'education_details.*.year' => ['required', 'string', 'digits:4'],
        ];
    }

    public function getEducationDetails()
    {
        return collect($this->education_details)
            ->map(function ($education) {
                ScholarEducationSubject::firstOrCreate(['name' => $education['subject']]);
                ScholarEducationDegree::firstOrCreate(['name' => $education['degree']]);
                ScholarEducationInstitute::firstOrCreate(['name' => $education['institute']]);
                return new EducationInfo($education);
            })->toArray();
    }

    protected function failedValidation(Validator $validator)
    {
        $response = redirect()->back()
            ->withInput($this->input())
            ->withErrors($validator->errors()->messages(), 'update');

        throw new ValidationException($validator, $response);
    }
}
