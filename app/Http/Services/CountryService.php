<?php

namespace App\Http\Services;

use App\Models\Country;

class CountryService
{
    /**
     * @var \App\Models\Country
     */
    protected $_entity;

    public function __construct(Country $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index($request)
    {
        $per_page = ($request->has('per_page') ? $request->per_page : 10);
        if($per_page > 100) {$per_page = 100;}

        /** @var \Illuminate\Database\Eloquent\Builder */
        $obj = $this->_entity::orderBy('name');

        $this->filterIndex($request, $obj);

        return $obj->paginate($per_page);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $obj
     */
    public function filterIndex($request, &$obj): void
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
