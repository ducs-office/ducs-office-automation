<?php

namespace App\Http\Requests\Scholar;

use App\Types\AdmissionMode;
use App\Types\FundingType;
use App\Types\ReservationCategory;
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
            'phone' => [Rule::requiredIf($scholar->phone != null)],
            'address' => [Rule::requiredIf($scholar->address != null)],
            'category' => [
                Rule::requiredIf($scholar->category != null),
                Rule::in(ReservationCategory::values()),
            ],
            'admission_mode' => [
                Rule::requiredIf($scholar->admission_mode != null),
                Rule::in(AdmissionMode::values()),
            ],
            'funding' => [
                Rule::requiredIf($scholar->funding != null),
                Rule::in(FundingType::values()),
            ],
            'avatar' => ['nullable', 'image'],
            'research_area' => [Rule::requiredIf($scholar->research_area != null)],
            'registration_date' => ['nullable', 'date', 'before:today'],
            'enrolment_id' => ['nullable', 'string', 'max:30'],
            'education_details' => ['required', 'array', 'max: 4', 'min:1'],
            'education_details.*' => ['required', 'array', 'size:4'],
            'education_details.*.degree' => ['required', 'string', 'max:190'],
            'education_details.*.subject' => ['required', 'string', 'max:190'],
            'education_details.*.institute' => ['required', 'string', 'max:190'],
            'education_details.*.year' => ['required', 'string'],
        ];
    }
}
