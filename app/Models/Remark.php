<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    protected $guarded = [];

    public function remarkable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(static function () {
            return new User([
                'name' => 'Deleted User',
                'email' => 'deleted_user@null.co',
            ]);
        });
    }
}
