<?php

namespace App;

use App\Concerns\Filterable;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use Filterable;
}
