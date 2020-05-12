<?php

namespace App\Http\Requests\Staff;

use App\Models\Cosupervisor;
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
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'email' => 'sometimes|required|' . Rule::unique('scholars')->ignore($this->route('scholar')),
            'supervisor_id' => ['sometimes', 'required', Rule::exists('users', 'id')->where('is_supervisor', true)],
            'cosupervisor_id' => [
                'sometimes', 'nullable', 'integer',
                function ($attribute, $value, $fail) {
                    $supervisor = $this->route('scholar')->currentSupervisor;
                    $cosup = Cosupervisor::find($value);
                    if (! $cosup) {
                        $fail('Invalid Cosupervior!');
                    }

                    if (
                        $cosup->person_type === User::class
                        && in_array($cosup->person_id, [$this->supervisor_id, $supervisor->id])
                    ) {
                        $fail('cosupervisor cannot be same as supervisor');
                    }
                },
            ],
        ];
    }
}
