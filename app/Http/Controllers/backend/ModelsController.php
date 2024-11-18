<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

use App\Models\Brands;
use App\Models\Models;
use DataTables;

class ModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
           
            $query = Models::select('models.id','models.brand_id','models.modelname','models.manufacture_year','models.modelimage','brands.brandname')
                    ->leftJoin('brands', 'models.brand_id', '=', 'brands.id')
                    ->orderBy('models.created_at', 'desc');
                  
                    if (!empty($request->get('search')['value'])) {
                        $searchTerm = $request->get('search')['value'];
                        $query->where(function($query) use ($searchTerm) {
                            $query->where('models.modelname', 'LIKE', "%{$searchTerm}%")
                                  ->orWhere('models.modelname', 'LIKE', "%{$searchTerm}%")
                                 
                                  ->orWhere('models.manufacture_year', 'LIKE', "%{$searchTerm}%");
                        });
                    }


            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    $url = asset('storage/models/' . $row->modelimage);
                    return '<img src="' . $url . '" alt="Image" width="50" height="50">';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<button class="editmodel btn btn-success btn-sm" data-id="'.$row->id.'" data-toggle="modal" data-target="#editModal">Edit</button> 
                    <button type="button" class="deletemodel btn btn-danger btn-sm" data-id="'.$row->id.'"data-toggle="modal" data-target="#deleteModal">Delete</button>';
                    return $actionBtn;
                })
                
                ->rawColumns(['action','image'])
                ->make(true);
        }
        return view('models/modelslist');
    }


    public function get_brands() {
        $brands = Brands::all();

        return response()->json($brands);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brandid'   =>'required',
            'modelname'   =>'required',
            'myear'    =>'required',
            'modelimage'    =>'required',
        ]);

        if ($validator -> fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            if ($request->hasFile('modelimage')) {
                $files = $request->file('modelimage');


                $filenameWithExt = $files->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $files->getClientOriginalExtension();
                $fileNameToStore = 'model'.'_'.uniqid() . '.' . $extension;
                $directory = 'public/models';
                $path = $files->storeAs($directory, $fileNameToStore);
            }

            $obj_models     = new Models();  
            $obj_models->brand_id = $request->brandid;
            $obj_models->modelname = $request->modelname;
            $obj_models->manufacture_year = $request->myear;
            $obj_models->modelimage = $fileNameToStore;
            $obj_models->save();

            Session::flash('message', 'Models Successfully Created');
            return redirect('admin/models');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $models = Models::where('id',$id)->first();
        return response()->json($models);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'brandid'   =>'required',
            'modelname'   =>'required',
            'myear'    =>'required',
        ]);
        if ($validator -> fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $fileNameToStore = '';
            if ($request->hasFile('modelimage')) {
                $files = $request->file('modelimage');


                $filenameWithExt = $files->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $files->getClientOriginalExtension();
                $fileNameToStore = 'model'.'_'.uniqid() . '.' . $extension;
                $directory = 'public/models';
                $path = $files->storeAs($directory, $fileNameToStore);
            }



            $obj_models     = Models::find($id);  
            $obj_models->brand_id = $request->brandid;
            $obj_models->modelname = $request->modelname;
            $obj_models->manufacture_year = $request->myear;
            if ($fileNameToStore !='') {
                $obj_models->modelimage = $fileNameToStore;
            }
            $obj_models->save();

            Session::flash('message', 'Models Updated Successfully');
            return redirect('admin/models');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Models::find($id)->delete();
        Session::flash('message', 'Models Deleted Successfully ');
        return redirect('admin/models');
    }
}
