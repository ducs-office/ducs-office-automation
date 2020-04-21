<?php

namespace App\Models;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use Filterable;
}
