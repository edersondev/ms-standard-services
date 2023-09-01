<?php

namespace App\Http\Services;

use App\Repositories\RegionRepository;

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
}
