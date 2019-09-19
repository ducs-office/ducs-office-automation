<?php
namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Concerns\Filterable;

class Model extends BaseModel {
    use Filterable;
}