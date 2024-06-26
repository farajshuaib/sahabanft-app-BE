<?php

namespace App\QueryFilters\Nfts;

use App\QueryFilters\Filter;

class IsVerified extends Filter
{
    protected function applyFilter($builder)
    {
        if (request($this->filterName()))
            return $builder->whereHas('creator.kyc', function ($query) {
                $query->where('status', request($this->filterName()));
            });
        return $builder->orderBy('id', 'desc');
    }

}
