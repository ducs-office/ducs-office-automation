<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReplaceScholarCosupervisorRequest extends FormRequest
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
        $rules = [];

        $scholar = $this->route('scholar');
        $validCosupervisorTypes = 'App\Models\Cosupervisor,App\Models\SupervisorProfile';

        if ($this->input('cosupervisor_profile_type') === 'App\Models\SupervisorProfile') {
            $rules['cosupervisor_profile_id'] = 'integer|exists:supervisor_profiles,id|
                                                Not in:' . $scholar->supervisor_profile_id;
        } else {
            $rules['cosupervisor_profile_id'] = 'nullable|integer|exists:cosupervisors,id';
        }

        if ($this->input('cosupervisor_profile_type') === $scholar->cosupervisor_profile_type) {
            $rules['cosupervisor_profile_id'] = $rules['cosupervisor_profile_id'] .
                                                '|Not in:' . $scholar->cosupervisor_profile_id . '|' .
                                                Rule::requiredIf(function () use ($scholar) {
                                                    return ! $scholar->cosupervisor;
                                                });
        }

        return [
            'cosupervisor_profile_type' => 'nullable|in:' . $validCosupervisorTypes,
        ] + $rules;
    }
}
