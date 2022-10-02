<?php

namespace App\Http\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Repositories\CountryRepository;

class CountryService
{
    /**
     * @var CountryRepository
     */
    protected $_repository;

    public function __construct(CountryRepository $repository)
    {
        $this->_repository = $repository;
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $per_page = ($request->has('per_page') ? $request->per_page : 10);
        if($per_page > 100) {$per_page = 100;}

        return $this->_repository
            ->index($request)
            ->paginate($per_page);
    }

}
