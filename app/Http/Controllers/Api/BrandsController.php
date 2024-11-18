<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

use App\Models\Brands;
use App\Models\Models;

// Resource
use App\Http\Resources\BrandsCollection;
use App\Http\Resources\ModelsCollection;

class BrandsController extends Controller
{
    public function brands_list(Request $request) {
        $brands = Brands::select('id','brandname','logo')->get();

        return response()->json([
            'code' =>200,
            'success' => true,
            'message' => '',
           'data' => BrandsCollection::collection($brands)
        ], Response::HTTP_OK);

    }


    public function branddetails(Request $request,$id) {
        $brand = Brands::select('id','brandname','logo')
                ->with('modelslist')
                ->where('id',$id)->first(); 

        return response()->json([
            'code' =>200,
            'success' => true,
            'message' => '',
           'data' => new BrandsCollection($brand)
        ], Response::HTTP_OK);
    }



    public function createmodel(Request $request)  {
        $validator = Validator::make($request->all(), [
            'brandid'   =>'required',
            'modelname'   =>'required',
            'myear'    =>'required',
        ]);

        if ($validator -> fails()) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Validation errors',
                'data'      => $validator->errors()
              ]));
        } else {
            $fileNameToStore = '';
            $obj_models     = new Models();  
            $obj_models->brand_id = $request->brandid;
            $obj_models->modelname = $request->modelname;
            $obj_models->manufacture_year = $request->myear;
            $obj_models->modelimage = $fileNameToStore;
            $obj_models->save();

            return response()->json([
                'code' =>200,
                'success' => true,
                'message' => 'Models Successfully Created',
               'data' => new ModelsCollection($obj_models)
            ], Response::HTTP_OK);
        }
    }
}
