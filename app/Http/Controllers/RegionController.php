<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegionRequest;
use Illuminate\Http\Request;
use App\Http\Services\RegionService;
use App\Http\Resources\ResponseResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Territory
 *
 * APIs for managing regions
 *
 * @subgroup Region
 */
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
     * This endpoint will bring a list of regions by iso_code from country.
     *
     * @queryParam country_iso string required The iso code of the country. Example: br
     *
     * @responseField id Unique identify
     * @responseField name The region name
     * @responseField region_code string The code of the region
     * @responseField country_id The country_id that the region belongs
     */
    public function index(Request $request)
    {
        // Query parameters
        $request->validate(['country_iso' => 'required']);

        return ResponseResource::collection($this->_service->index($request));
    }

    /**
     * New region
     *
     * This endpoint will create a new region.
     *
     * @bodyParam name string required The region name. No-example
     * @bodyParam region_code string The code of the region
     * @bodyParam country_id int required The country_id that the region belongs
     *
     * @responseField id Unique identify
     * @responseField name The region name
     * @responseField region_code string The code of the region
     * @responseField country_id The country_id that the region belongs
     *
     * @response 201 {
     *      "id": 17,
     *      "name": "Westeros",
     *      "region_code": "WES",
     *      "country_id": 58
     *  }
     * @response 422 scenario="Unprocessable Content"
     * @response 500 scenario="Internal Error"
     */
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

    /**
     * Get Region
     *
     * Retrieve details from a region.
     *
     * @urlParam id integer required The ID of the region
     *
     * @responseField id Unique identify
     * @responseField name The region name
     * @responseField region_code string The code of the region
     * @responseField country_id The country_id that the region belongs
     *
     * @response 404 scenario="Not Found"
     * @response 500 scenario="Internal Error"
     */
    public function show(int $id)
    {
        return ResponseResource::make($this->_service->show($id));
    }

    /**
     * Update Region
     *
     * @urlParam id integer required The ID of the region
     *
     * @bodyParam name string The region name. No-example
     * @bodyParam region_code string The code of the region
     * @bodyParam country_id int The country_id that the region belongs
     *
     * @response 204 scenario="Success"
     * @response 404 scenario="Not Found"
     * @response 500 scenario="Internal Error"
     */
    public function update(int $id, RegionRequest $request)
    {
        $this->_service->update($id, $request);

        return response()->noContent();
    }

    /**
     * Delete Region
     *
     * @urlParam id integer required The ID of the region
     *
     * @response 204 scenario="Success"
     * @response 404 scenario="Not Found"
     * @response 500 scenario="Internal Error"
     */
    public function destroy(int $id)
    {
        $this->_service->destroy($id);

        return response()->noContent();
    }
}
