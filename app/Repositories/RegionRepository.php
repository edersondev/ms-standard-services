<?php

namespace App\Repositories;

use App\Models\Region;

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
}
