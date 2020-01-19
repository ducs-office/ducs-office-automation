
<?php

namespace App;

use App\Course;

use Illuminate\Database\Eloquent\Model;

class ProgrammeRevision extends Model
{
    protected $fillable = ['revised_at'];

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot(['semester']);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
