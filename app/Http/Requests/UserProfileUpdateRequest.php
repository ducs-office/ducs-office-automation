<?php

namespace App\Http\Requests;

use App\Models\Pivot\CourseProgrammeRevision;
use App\Models\ProgrammeRevision;
use App\Types\Designation;
use App\Types\TeacherStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->user();

        $rules = [
            'phone' => ['sometimes', Rule::requiredIf($user->phone != null), 'digits:10'],
            'address' => ['sometimes', Rule::requiredIf($user->address != null)],
            'status' => [
                'sometimes',
                Rule::requiredIf($user->status != null),
                Rule::in(TeacherStatus::values()),
            ],
            'designation' => [
                'sometimes',
                'string',
                Rule::requiredIf($user->designation != null),
            ],
            'avatar' => ['nullable', 'file', 'image', 'max:200'],
        ];

        if ($user->isExternal()) {
            $rules['affiliation'] = ['sometimes', 'required', 'string'];
        } else {
            $rules['college_id'] = ['sometimes', Rule::requiredIf($user->college_id != null), 'numeric', 'exists:colleges,id'];
        }

        if ($user->isCollegeTeacher() || $user->isFacultyTeacher()) {
            $rules['status'] = [
                'sometimes',
                Rule::requiredIf($user->status != null),
                Rule::in(TeacherStatus::values()),
            ];
            $rules['designation'][] = Rule::in(Designation::values());
        }

        return $rules;
    }

    public function uploadAvatar()
    {
        if (!$this->hasFile('avatar')) {
            return null;
        }

        $avatarFile = $this->file('avatar');
        $path = 'users/avatars';
        $filename = md5(strtolower($this->route('user')->email))
            . '.' . $avatarFile->getClientOriginalExtension();

        return $avatarFile->storeAs($path, $filename);
    }

    protected function failedValidation(Validator $validator)
    {
        $response = redirect()->back()
            ->withInput($this->input())
            ->withErrors($validator->errors()->messages(), 'update');

        throw new ValidationException($validator, $response);
    }
}
