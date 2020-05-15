<?php

namespace App\Http\Requests;

use App\Models\ExternalAuthority;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
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
        $userConflicts = [$this->route('scholar')->currentSupervisor->id];
        $externalConflicts = [];

        if ($cosupervisor = $this->route('scholar')->currentCosupervisor) {
            if ($cosupervisor->person_type === User::class) {
                $userConflicts[] = $this->route('scholar')->currentCosupervisor->person_id;
            } else {
                $externalConflicts[] = $this->route('scholar')->currentCosupervisor->person_id;
            }
        }

        return [
            'advisors' => ['required', 'array', 'max:2'],
            'advisors.*.user_id' => [
                'sometimes', 'required', 'integer', 'distinct',
                Rule::notIn($userConflicts),
                Rule::exists(User::class, 'id')
                    ->where(function ($query) {
                        $query->where('is_cosupervisor', 1)
                            ->orWhere('is_supervisor', 1);
                    }),
            ],
            'advisors.*.external_id' => [
                'sometimes', 'required', 'integer', 'distinct',
                Rule::notIn($externalConflicts),
                Rule::exists(ExternalAuthority::class, 'id'),
            ],
            'advisors.*.name' => [
                'required_without_all:advisors.*.user_id,advisors.*.external_id',
            ],
            'advisors.*.designation' => [
                'required_without_all:advisors.*.user_id,advisors.*.external_id',
            ],
            'advisors.*.affiliation' => [
                'required_without_all:advisors.*.user_id,advisors.*.external_id',
            ],
            'advisors.*.email' => [
                'required_without_all:advisors.*.user_id,advisors.*.external_id',
                'distinct',
                'email', 'unique:external_authorities,email',
            ],
            'advisors.*.phone' => ['nullable', 'string'],
        ];
    }
}
