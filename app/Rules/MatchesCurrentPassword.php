<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MatchesCurrentPassword implements Rule
{
    private $password_field;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($password_field = null)
    {
        $this->password_field = $password_field;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, Auth::user()->{$this->password_field ?? $attribute});
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Incorrect Password';
    }
}
