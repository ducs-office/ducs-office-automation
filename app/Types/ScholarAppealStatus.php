<?php

namespace App\Types;

use App\Types\BaseEnumType;

class ScholarAppealStatus extends BaseEnumType
{
    const APPLIED = 'applied';
    const RECOMMENDED = 'recommended';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const COMPLETED = 'completed';
}
