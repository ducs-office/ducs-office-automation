<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'category',
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->roles()->sync([]);
            $user->remarks()->update(['user_id' => null]);
        });
    }

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

    public function createdIncomingLetters()
    {
        return $this->hasMany(IncomingLetter::class, 'creator_id');
    }
}
