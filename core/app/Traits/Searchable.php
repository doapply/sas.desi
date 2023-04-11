<?php

namespace App\Traits;

/**
 * Apply search-ability on the model to search by column name on any model that uses this trait
 */
trait Searchable
{
    public function scopeSearchable($query, $columns, $like = true)
    {
        $search = request()->search;
        $search = !$like ?: "%$search%";
        foreach ($columns as $column) {
            $query->orWhere($column, 'LIKE', $search);
        }
        return $query;
    }

    public function scopeFilter($query, $columns)
    {
        foreach ($columns as $columName) {
            $columns = array_keys(request()->all());
            if (in_array($columName, $columns) && request()->$columName != null) {
                $query->where($columName, 'like', '%' . request()->$columName . '%');
            }
        }
        return $query;
    }

    function scopeDateFilter($query)
    {
        if (!request()->date) {
            return $query;
        }
        $date      = explode(',', request()->date);
        $startDate = $date[0];
        $endDate   = @$date[1];

        request()->merge(['start_date' => $startDate, 'end_date', $endDate]);

        request()->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d',
        ]);

        return  $query->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate ?? $startDate);
    }
}
