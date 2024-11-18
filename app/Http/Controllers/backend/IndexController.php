<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Resources\BrandsCollection;

use App\Models\Brands;

class IndexController extends Controller
{
    public function index() {
        return view('index');
    }


   public function brandslist(Request $request) {
    return view('brands');
    }


    public function get_allbrands(Request $request) {
        $brands = Brands::all();

       return response()->json([
        'code' =>200,
        'success' => true,
        'message' => '',
       'data' => BrandsCollection::collection($brands)
    ], Response::HTTP_OK);
    }


    public function getbrandsby_aphebet($key) {
        $brands = Brands::whereRaw("LOWER(brandname) LIKE ?", [strtolower($key) . '%'])
                  ->get();

        return response()->json([
            'code' =>200,
            'success' => true,
            'message' => '',
            'data' => BrandsCollection::collection($brands)
        ], Response::HTTP_OK);
        
        
        
    }

}
