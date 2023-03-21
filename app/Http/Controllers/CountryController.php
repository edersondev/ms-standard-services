<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\CountryService;
use App\Http\Resources\ResponseCollection;

class CountryController extends Controller
{
    protected $_service;

    public function __construct(CountryService $service)
    {
        $this->_service = $service;
    }

    /**
     * List of countries
     *
     * This endpoint will bring a list of countries around the World, you'll get a 200 OK response.
     *
     * @responseField id Unique identify
     * @responseField name The country name
     * @responseField iso_code Alpha-2 codes are two-letter country codes defined in ISO 3166-1
     * @responseField iso_code3 Alpha-3 codes are three-letter country codes defined in ISO 3166-1
     * @responseField number_code Numeric (or numeric-3) codes are three-digit country codes defined in ISO 3166-1
     * @responseField dial International telephone dialing codes
     */
    public function index(Request $request)
    {
        return new ResponseCollection($this->_service->index($request));
    }
}
