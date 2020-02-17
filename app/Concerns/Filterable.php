<?php

namespace App\Concerns;

trait Filterable
{
    /**
     * variable controls filters
     *
     * @var array
     */
    protected $allowedFilters = [];

    public function scopeApplyFilter($query, $filters)
    {
        $operators = config('database.operators');

        $filters = array_intersect_key($filters ?? [], $this->allowedFilters);

        foreach ($filters as $field => $comparators) {
            foreach ($comparators as $operator => $value) {
                if (trim($value) !== '') {
                    $query->where($field, $operators[$operator], $value);
                }
            }
        }
    }
}
