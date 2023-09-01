<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\RegionService;

class RegionController extends Controller
{
    /**
     * @var RegionService
     */
    protected $_service;

    public function __construct(RegionService $service)
    {
        $this->_service = $service;
    }
}
