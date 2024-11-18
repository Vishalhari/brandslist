<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

use App\Models\Brands;
use DataTables;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brands::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    $url = asset('storage/brands/' . $row->logo);
                    return '<img src="' . $url . '" alt="Image" width="50" height="50">';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<button class="editbrand btn btn-success btn-sm" data-id="'.$row->id.'" data-toggle="modal" data-target="#editModal">Edit</button> 
                    <button type="button" class="deletebrand btn btn-danger btn-sm" data-id="'.$row->id.'"data-toggle="modal" data-target="#deleteModal">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action','logo'])
                ->make(true);
        }
        return view('brands/brandlist');
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
            'brandname'   =>'required',
            'brandlogo'    =>'required',
        ]);
        if ($validator -> fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            if ($request->hasFile('brandlogo')) {
                $files = $request->file('brandlogo');


                $filenameWithExt = $files->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $files->getClientOriginalExtension();
                $fileNameToStore = 'brand'.'_'.uniqid() . '.' . $extension;
                $directory = 'public/brands';
                $path = $files->storeAs($directory, $fileNameToStore);
            }
            $obj_brands     = new Brands();  
            $obj_brands->brandname = $request->brandname;
            $obj_brands->logo = $fileNameToStore;
            $obj_brands->save();

            Session::flash('message', 'Brands Successfully Created');
            return redirect('admin/brands');


        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brands = Brands::where('id',$id)->first();

        return response()->json($brands);
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
            'brandname'   =>'required',
        ]);

        if ($validator -> fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $fileNameToStore = '';
            if ($request->hasFile('brandlogo')) {
                $files = $request->file('brandlogo');


                $filenameWithExt = $files->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $files->getClientOriginalExtension();
                $fileNameToStore = 'brand'.'_'.uniqid() . '.' . $extension;
                $directory = 'public/brands';
                $path = $files->storeAs($directory, $fileNameToStore);
            }
            $obj_brands     = Brands::find($id);  
            $obj_brands->brandname = $request->brandname;
            if ($fileNameToStore !='') {
                $obj_brands->logo = $fileNameToStore;
            }
            $obj_brands->save();

            Session::flash('message', 'Brands Updated Successfully');
            return redirect('admin/brands');


        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Brands::find($id)->delete();
        Session::flash('message', 'Brands Deleted Successfully ');
        return redirect('admin/brands');
    }
}
