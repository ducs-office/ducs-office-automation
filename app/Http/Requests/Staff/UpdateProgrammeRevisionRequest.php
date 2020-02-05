<?php

namespace App\Http\Requests\Staff;

use App\Course;
use App\CourseProgrammeRevision;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgrammeRevisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $programme = $this->route('programme');
        $programme_revision = $this->route('programme_revision');

        return [
            'revised_at' => ['sometimes', 'required', 'date',
                function ($attribute, $value, $fail) use ($programme, $programme_revision) {
                    $revisions = $programme->revisions
                        ->filter(function ($revision) use ($programme_revision) {
                            return $revision->id != $programme_revision->id;
                        })
                        ->map->toArray();
                    if ($revisions->contains('revised_at', $value)) {
                        $fail($attribute.' is invalid');
                    }
                },
            ],
            'semester_courses' => [
                'sometimes', 'required', 'array',
                'size:'.(($programme->duration) * 2),
            ],
            'semester_courses.*' => ['sometimes', 'required', 'array', 'min:1'],
            'semester_courses.*.*' => ['sometimes', 'numeric', 'distinct', 'exists:courses,id',
                function ($attribute, $value, $fail) use ($programme) {
                    $courses = CourseProgrammeRevision::all();
                    foreach ($courses as $course) {
                        if ($value == $course->course_id && Course::find($course->course_id)->programme_revisions()->first()->programme_id != $programme->id) {
                            $fail($attribute.'is invalid');
                        }
                    }
                },
            ],
        ];
    }
}
