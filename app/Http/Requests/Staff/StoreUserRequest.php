<?php

namespace App\Http\Requests\Staff;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $categories = implode(',', array_keys(config('options.users.categories')));

        return [
            'name' => ['required', 'string', 'min:3', 'max:190'],
            'email' => ['required', 'string', 'min:3', 'max:190', 'email', 'unique:users'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'integer', 'exists:roles,id'],
            'category' => ['required', 'in:' . $categories],
        ];
    }
}
