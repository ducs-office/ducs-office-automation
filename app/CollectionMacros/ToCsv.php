<?php

namespace App\CollectionMacros;

use Countable;
use Illuminate\Support\Collection;

class ToCsv
{
    public function __invoke()
    {
        return function ($fields, $seperator = ',', $lineEndings = "\n") {
            $table = [array_keys($fields)];

            foreach ($this->items as $record) {
                $row = collect($fields)->map(static function ($attribute) use ($record) {
                    return collect([$record])->pluck($attribute)->flatten();
                });
                $depth = $row->max->count();

                $flatRows = Collection::times($depth, static function ($index) use ($row) {
                    return $row->map(function ($item) use ($index) {
                        return $item[($index-1) % count($item)];
                    });
                })->toArray();

                array_push($table, ...$flatRows);
            }

            return implode(
                $lineEndings,
                array_map(function ($row) use ($seperator) {
                    return implode($seperator, $row);
                }, $table)
            );
        };
    }
}
