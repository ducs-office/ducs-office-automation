<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    /**
     * variable controls filters
     *
     * @var array
     */
    public function scopeFilter(Builder $query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->filters)
            ->then(function ($query) {
                return $query;
            });
    }
}
