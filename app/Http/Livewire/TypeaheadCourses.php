<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Pivot\CourseProgrammeRevision;
use Livewire\Component;

class TypeaheadCourses extends Component
{
    protected $listeners = ['programmeRevisionSelected'];

    public $open;
    public $input_id;
    public $name;
    public $multiple;
    public $limit;
    public $value;
    public $placeholder;
    public $searchPlaceholder;
    public $programmeRevisionId;

    public $query = '';

    public function mount(
        $open = false,
        $id = 'course-typeahead',
        $name = 'course_id',
        $limit = 15,
        $value = null,
        $multiple = false,
        $placeholder = '',
        $searchPlaceholder = ''
    ) {
        $this->open = $open;
        $this->input_id = $id;
        $this->name = $name;
        $this->multiple = $multiple;
        $this->limit = $limit;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->searchPlaceholder = $searchPlaceholder;
    }

    public function render()
    {
        return view('livewire.typeahead-courses', [
            'courses' => $this->getCourses(),
        ]);
    }

    protected function getCourses()
    {
        $courses = Course::select(['id', 'code', 'name']);

        if ($this->programmeRevisionId != null && is_numeric($this->programmeRevisionId)) {
            $courses->whereIn('id', $this->programmeSubquery);
        }

        return $courses->where(function ($query) {
            $query->where('code', 'like', $this->query . '%')
                ->orWhere('name', 'like', '%' . $this->query . '%');
        })
        ->orWhereIn('id', $this->multiple ? $this->value : [$this->value])
        ->get();
    }

    protected function getProgrammeSubqueryProperty()
    {
        return CourseProgrammeRevision::query()
            ->select('course_id')
            ->whereProgrammeRevisionId($this->programmeRevisionId);
    }

    public function updatedQuery()
    {
        $this->open = true;
    }

    public function programmeRevisionSelected($programmeRevisionId)
    {
        $this->programmeRevisionId = $programmeRevisionId;
    }
}
