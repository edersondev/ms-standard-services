<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\RegionService;
use App\Http\Resources\ResponseResource;

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

    public function index(Request $request, string $country_iso)
    {
        $request->merge(['country_iso' => $country_iso]);

        return ResponseResource::collection($this->_service->index($request));
    }
}
