<?php

namespace App\Http\Services;

use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class RegionService
{
    /**
     * @var RegionRepository
     */
    protected $_repository;

    public function __construct(RegionRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function index(Request $request): Collection|array
    {
        return $this->_repository->index($request)->get();
    }

    public function store(Request $request): \App\Models\Region
    {
        return $this->_repository->store($request);
    }
}
