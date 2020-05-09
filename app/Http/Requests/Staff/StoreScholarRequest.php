<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [];
        $validCosupervisorTypes = 'App\Models\Cosupervisor,App\Models\SupervisorProfile';

        if ($this->input('cosupervisor_profile_type') === 'App\Models\SupervisorProfile') {
            $rules['cosupervisor_profile_id'] = 'integer|exists:supervisor_profiles,id|Not in:' . $this->input('supervisor_profile_id');
        } else {
            $rules['cosupervisor_profile_id'] = 'nullable|integer|exists:cosupervisors,id';
        }

        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:scholars',
            'term_duration' => 'required| integer| gt: 0',
            'supervisor_profile_id' => 'required| exists:supervisor_profiles,id',
            'cosupervisor_profile_type' => 'nullable| in:' . $validCosupervisorTypes,
        ] + $rules;
    }
}
