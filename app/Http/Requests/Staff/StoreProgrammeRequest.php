<?php

namespace App\Http\Requests\Staff;

use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use App\Types\ProgrammeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProgrammeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Programme::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', 'min:3', 'max:60', 'unique:programmes,code'],
            'wef' => ['required', 'date'],
            'name' => ['required', 'min:3', 'max:190'],
            'type' => ['required', Rule::in(ProgrammeType::values())],
            'duration' => ['required', 'integer'],
            'semester_courses' => ['required', 'array', 'size:' . ($this->duration * 2)],
            'semester_courses.*' => ['required', 'array', 'min:1'],
            'semester_courses.*.*' => ['numeric', 'distinct', 'exists:courses,id',
                Rule::unique(CourseProgrammeRevision::class, 'course_id'),
            ],
        ];
    }

    public function createProgrammeRevision(Programme $programme)
    {
        $revision = $programme->revisions()->create(['revised_at' => $this->wef]);

        collect($this->semester_courses)->map(function ($courses, $semester) use ($revision) {
            return $revision->courses()->attach($courses, ['semester' => $semester]);
        });
    }
}
