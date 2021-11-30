<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Region;
use App\Http\Resources\RegionResource;
use Auth;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::all();
        $regions =  RegionResource::collection($regions);
        return response()->json([
            'success'=> true,
            'data'=> $regions
        ],200);
    }
}
