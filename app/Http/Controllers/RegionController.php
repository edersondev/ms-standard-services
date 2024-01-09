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

    /**
     * List of regions by country
     *
     * This endpoint will bring a list of regions by iso_code from country, you'll get a 200 OK response.
     *
     * @queryParam country_iso string required The iso code of the country. Example: br
     *
     * @responseField id Unique identify
     * @responseField name The region name
     * @responseField region_code The code of the region
     * @responseField country_id The country_id that the region belongs
     */
    public function index(Request $request)
    {
        // Query parameters
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

    public function update(int $id, RegionRequest $request)
    {
        $this->_service->update($id, $request);

        return response()->noContent();
    }

    public function destroy(int $id)
    {
        $this->_service->destroy($id);

        return response()->noContent();
    }
}
