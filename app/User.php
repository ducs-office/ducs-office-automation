<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->roles()->sync([]);
            $user->remarks()->update(['user_id' => null]);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function remarks()
    {
        return $this->hasMany(Remark::class, 'user_id');
    }

    public function sentLetters()
    {
        return $this->hasMany(OutgoingLetter::class, 'sender_id');
    }

    public function createdOutgoingLetters()
    {
        return $this->hasMany(OutgoingLetter::class, 'creator_id');
    }

    public function receivedLetters()
    {
        return $this->hasMany(IncomingLetter::class, 'recipient_id');
    }
}
