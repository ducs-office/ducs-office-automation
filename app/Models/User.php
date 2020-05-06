<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Concerns\ExistsAsCosupervisor;
use App\Concerns\SupervisesScholars;
use App\Types\UserType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SupervisesScholars, ExistsAsCosupervisor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type',
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
        'type' => CustomType::class . ':' . UserType::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(static function ($user) {
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
