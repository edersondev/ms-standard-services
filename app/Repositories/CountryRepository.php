<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CountryRepository
{
    /**
     * @var Country
     */
    protected $_entity;

    public function __construct(Country $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * @param Request $request
     * @return Builder
     */
    public function index(Request $request): Builder
    {
        /** @var Builder */
        $obj = $this->_entity::orderBy('name');

        $this->filterIndex($request, $obj);

        return $obj;
    }

    /**
     * @param Request $request
     * @param Builder $obj
     */
    public function filterIndex(Request $request, Builder &$obj): void
    {
        $obj->when(
            $request->name,
            fn (Builder $query) => $query->where('name', 'like', "%{$request->name}%")
        );

        $obj->when(
            $request->iso_code,
            fn (Builder $query) => $query->where('iso_code', $request->iso_code)
        );

        $obj->when(
            $request->iso_code3,
            fn (Builder $query) => $query->where('iso_code3', $request->iso_code3)
        );

        $obj->when(
            $request->number_code,
            fn (Builder $query) => $query->where('number_code', $request->number_code)
        );
    }
}
