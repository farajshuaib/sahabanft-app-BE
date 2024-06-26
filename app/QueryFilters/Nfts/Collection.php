<?php


namespace App\QueryFilters\Nfts;

use App\QueryFilters\Filter;

class Collection extends Filter
{
    protected function applyFilter($builder)
    {
        return $builder->whereHas('collection', function ($query) {
            $query->where('id', request($this->filterName()));
        });
    }
}
