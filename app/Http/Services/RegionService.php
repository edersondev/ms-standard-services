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

    public function show(int $id): \App\Models\Region
    {
        return $this->_repository->show($id);
    }

    public function update(int $id, Request $request): void
    {
        $this->_repository->update($id, $request);
    }

    public function destroy(int $id): void
    {
        $this->_repository->destroy($id);
    }
}
