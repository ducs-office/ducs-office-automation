<?php

namespace App\Models;

use App\Concerns\ActsAsCosupervisor;
use Illuminate\Database\Eloquent\Model;

class ExternalAuthority extends Model
{
    use ActsAsCosupervisor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'designation',
        'affiliation',
        'phone',
        'is_cosupervisor',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_cosupervisor' => 'boolean',
    ];
}
