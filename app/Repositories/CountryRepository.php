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
        if($request->has('name') && !empty($request->name)){
            $obj->where('name','like',"%{$request->name}%");
        }

        if($request->has('iso_code') && !empty($request->iso_code)){
            $obj->where('iso_code', $request->iso_code);
        }

        if($request->has('iso_code3') && !empty($request->iso_code3)){
            $obj->where('iso_code3', $request->iso_code3);
        }

        if($request->has('number_code') && !empty($request->number_code)){
            $obj->where('number_code', $request->number_code);
        }
    }
}