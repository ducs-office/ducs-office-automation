<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgrammeRevision;

class ProgrammeRevisionCourseController extends Controller
{
    public function index(ProgrammeRevision $programmeRevision)
    {
        return $programmeRevision->courses;
    }
}
