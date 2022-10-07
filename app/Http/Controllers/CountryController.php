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

    public function index(Request $request)
    {
        return new ResponseCollection($this->_service->index($request));
    }
}
