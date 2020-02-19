<?php

namespace App\Http\Requests\Teacher;

use App\CourseProgrammeRevision;
use App\ProgrammeRevision;
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
        $designations = array_keys(config('options.teachers.designations'));

        return [
            'phone_no' => [Rule::requiredIf($this->user()->profile->phone_no), 'string'],
            'address' => [Rule::requiredIf($this->user()->profile->address), 'string'],
            'designation' => [Rule::requiredIf($this->user()->profile->designation), 'string', Rule::in($designations)],
            'college_id' => [Rule::requiredIf($this->user()->profile->college_id), 'numeric', 'exists:colleges,id'],
            'teaching_details' => ['nullable', 'array'],
            'teaching_details.*.programme_revision' => ['required_with:teaching_details.*', 'numeric', 'exists:programme_revisions,id'],
            'teaching_details.*.course' => ['required_with:teaching_details.*', 'numeric', 'exists:course_programme_revision,course_id'],
            'teaching_details.*' => ['nullable', 'array',
                static function ($attribute, $value, $fail) {
                    $revision = ProgrammeRevision::find($value['programme_revision']);
                    if (! $revision->courses->contains($value['course'] ?? '')) {
                        return $fail('course does not belong to the programme in' . $attribute);
                    }
                },
            ],
            'profile_picture' => ['nullable', 'file', 'image'],
        ];
    }

    public function getTeachingRecord()
    {
        return collect($this->teaching_details)
            ->map(static function ($teaching_detail) {
                return CourseProgrammeRevision::where(
                    'programme_revision_id',
                    $teaching_detail['programme_revision']
                )->where('course_id', $teaching_detail['course'])
                ->first()->id;
            })->toArray();
    }

    public function getProfilePicture()
    {
        return [
            'original_name' => $this->file('profile_picture')->getClientOriginalName(),
            'path' => $this->file('profile_picture')->store('/teacher_attachments/profile_picture'),
        ];
    }
}
