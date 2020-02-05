<?php

namespace App\Http\Requests\Staff;

use App\Course;
use App\CourseProgrammeRevision;
use App\ProgrammeRevision;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgrammeRevisionRequest extends FormRequest
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

        return [
            'revised_at' => ['required', 'date',
                function ($attribute, $value, $fail) use ($programme) {
                    $revisions = $programme->revisions->map->toArray();
                    if ($revisions->contains('revised_at', $value)) {
                        $fail($attribute.' is invalid');
                    }
                },
            ],
            'semester_courses' => [
                'sometimes', 'required', 'array',
                'size:'.($programme->duration * 2),
            ],
            'semester_courses.*' => ['required', 'array', 'min:1'],
            'semester_courses.*.*' => ['numeric', 'distinct', 'exists:courses,id',
                function ($attribute, $value, $fail) use ($programme) {
                    $courses = CourseProgrammeRevision::all();
                    foreach ($courses as $course) {
                        if ($value == $course->course_id && Course::find($course->course_id)->programme_revisions()->first()->programme_id != $programme->id) {
                            $fail($attribute.' is invalid');
                        }
                    }
                },
            ],
        ];
    }
}
