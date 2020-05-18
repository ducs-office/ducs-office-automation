<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeScholarAdvisorsRequest extends FormRequest
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
        $allowedCategories = [
            UserCategory::FACULTY_TEACHER,
            UserCategory::COLLEGE_TEACHER,
            UserCategory::EXTERNAL,
        ];

        $conflicts = [
            $this->route('scholar')->currentSupervisor->id,
            optional($this->route('scholar')->currentCosupervisor)->id,
        ];

        return [
            'advisors' => ['required', 'array', 'max:2'],
            'advisors.*' => [
                'required', 'integer', 'distinct',
                Rule::notIn($conflicts),
                Rule::exists(User::class, 'id')
                    ->where(function ($query) use ($allowedCategories) {
                        $query->where('is_supervisor', 1)
                            ->orWhere('is_cosupervisor', 1)
                            ->orWhereIn('category', $allowedCategories);
                    }),
            ],
            // 'advisors.*.name' => [
            //     'required_without_all:advisors.*.user_id,advisors.*.external_id',
            // ],
            // 'advisors.*.designation' => [
            //     'required_without_all:advisors.*.user_id,advisors.*.external_id',
            // ],
            // 'advisors.*.affiliation' => [
            //     'required_without_all:advisors.*.user_id,advisors.*.external_id',
            // ],
            // 'advisors.*.email' => [
            //     'required_without_all:advisors.*.user_id,advisors.*.external_id',
            //     'distinct', 'email', 'unique:users,email',
            // ],
            // 'advisors.*.phone' => ['nullable', 'string'],
            // 'advisors.*.address' => ['nullable', 'string'],
        ];
    }
}
