<?php

namespace App\Http\Requests;

use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\ProgrammeRevision;
use App\Types\Designation;
use App\Types\TeacherStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->user();

        return [
            'phone' => ['sometimes', Rule::requiredIf($user->phone != null)],
            'address' => ['sometimes', Rule::requiredIf($user->address != null)],
            'status' => [
                'sometimes',
                Rule::requiredIf($user->status != null),
                Rule::in(TeacherStatus::values()),
            ],
            'designation' => [
                'sometimes',
                Rule::requiredIf($user->designation != null),
                Rule::in(Designation::values()),
            ],
            'college_id' => [
                'sometimes',
                Rule::requiredIf($user->college_id != null),
                'numeric', 'exists:colleges,id',
            ],
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
            'avatar' => ['nullable', 'file', 'image'],
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

    public function uploadAvatar()
    {
        if (! $this->hasFile('avatar')) {
            return null;
        }

        $avatarFile = $this->file('avatar');
        $path = 'users/avatars';
        $filename = md5(strtolower($this->route('user')->email))
            . '.' . $avatarFile->getClientOriginalExtension();

        return $avatarFile->storeAs($path, $filename);
    }
}
