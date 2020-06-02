<?php

namespace App\Http\Requests\Staff;

use App\Models\Cosupervisor;
use App\Models\ExternalAuthority;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScholarRequest extends FormRequest
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
        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:190'],
            'last_name' => ['sometimes', 'required', 'string', 'max:190'],
            'email' => ['sometimes', 'required', Rule::unique('scholars')->ignore($this->route('scholar'))],
            'supervisor_id' => [
                'sometimes', 'required',
                Rule::exists('users', 'id')->where('is_supervisor', true),
            ],
            'cosupervisor_id' => [
                'nullable', 'different:supervisor_id',
                Rule::notIn([optional($this->route('scholar')->currentSupervisor)->id]),
                Rule::exists(User::class, 'id')->where(function ($query) {
                    return $query->where('is_cosupervisor', true)
                        ->orWhere('is_supervisor', true);
                }),
            ],
        ];
    }
}
