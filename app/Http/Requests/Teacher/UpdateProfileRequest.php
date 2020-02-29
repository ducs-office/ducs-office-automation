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
            'phone_no' => [Rule::requiredIf($this->user()->profile->phone_no != null)],
            'address' => [Rule::requiredIf($this->user()->profile->address != null)],
            'designation' => [Rule::requiredIf($this->user()->profile->designation != null),  Rule::in($designations)],
            'college_id' => [Rule::requiredIf($this->user()->profile->college_id != null), 'numeric', 'exists:colleges,id'],
            'teaching_details' => ['nullable', 'array'],
            'teaching_details.*.programme_revision_id' => ['nullable', 'numeric', 'exists:programme_revisions,id'],
            'teaching_details.*.course_id' => ['nullable', 'numeric', 'exists:course_programme_revision,course_id'],
            'teaching_details.*' => ['nullable', 'array',
                static function ($attribute, $value, $fail) {
                    if (! isset($value['programme_revision_id'])) {
                        return true;
                    }
                    $revision = ProgrammeRevision::find($value['programme_revision_id']);
                    if (! $revision->courses->contains($value['course_id'] ?? '')) {
                        return $fail('course does not belong to the programme in' . $attribute);
                    }
                },
            ],
            'profile_picture' => ['nullable', 'file', 'image'],
        ];
    }

    public function getTeachingRecord()
    {
        return collect($this->teaching_details ?? [])
            ->filter(function ($detail) {
                return isset($detail['programme_revision_id']) && isset($detail['course_id']);
            })
            ->map(static function ($teaching_detail) {
                return CourseProgrammeRevision::where(
                    'programme_revision_id',
                    $teaching_detail['programme_revision_id']
                )->where('course_id', $teaching_detail['course_id'])
                ->first()
                ->only(['programme_revision_id', 'course_id', 'semester']);
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
