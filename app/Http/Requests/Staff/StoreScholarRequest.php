<?php

namespace App\Http\Requests\Staff;

use App\Models\Cosupervisor;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScholarRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:scholars',
            'term_duration' => 'required| integer| gt: 0',
            'supervisor_id' => ['required', Rule::exists('users', 'id')->where('is_supervisor', true)],
            'cosupervisor_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $cosup = Cosupervisor::find($value);
                    if (! $cosup) {
                        $fail('Invalid Cosupervior!');
                    }

                    if ($cosup->person_type === User::class && (int) $cosup->person_id === (int) $this->supervisor_id) {
                        $fail('cosupervisor cannot be same as supervisor');
                    }
                },
            ],
        ];
    }
}
