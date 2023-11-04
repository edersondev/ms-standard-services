<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegionRequest;
use Illuminate\Http\Request;
use App\Http\Services\RegionService;
use App\Http\Resources\ResponseResource;
use Symfony\Component\HttpFoundation\Response;

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

    public function index(Request $request)
    {
        $request->validate(['country_iso' => 'required']);

        return ResponseResource::collection($this->_service->index($request));
    }

    public function store(RegionRequest $request)
    {
        $region = $this->_service->store($request);
        $location = route('regions.show', ['region' => $region->id]);

        return response(
            ResponseResource::make($region),
            Response::HTTP_CREATED,
            ['Location' => $location]
        );
    }

    public function show(int $id)
    {
        return ResponseResource::make($this->_service->show($id));
    }
}
