<?php

namespace App;

use App\Programme;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class College extends Model
{
    protected $fillable = [
        'code', 'name', 'address', 'principal_name',
        'principal_phones', 'principal_emails', 'website',
    ];

    public function programmes()
    {
        return $this->belongsToMany(Programme::class, 'colleges_programmes', 'college_id', 'programme_id');
    }

    public function setPrincipalPhonesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['principal_phones'] = implode('|', $value);
        } else {
            $this->attributes['principal_phones'] = $value;
        }
    }

    public function getPrincipalPhonesAttribute($value)
    {
        return explode('|', $value);
    }

    public function setPrincipalEmailsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['principal_emails'] = implode('|', $value);
        } else {
            $this->attributes['principal_emails'] = $value;
        }
    }
    public function getPrincipalEmailsAttribute($value)
    {
        return explode('|', $value);
    }
}
