<?php

namespace App\Repositories;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class RegionRepository
{
    /**
     * @var Region
     */
    protected $_entity;

    public function __construct(Region $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * @param Request $request
     * @return Builder
     */
    public function index(Request $request): Builder
    {
        return $this->_entity::whereHas(
            'country',
            fn (Builder $query) => $query->where('iso_code', $request->country_iso)
        )->orderBy('name');
    }
}
