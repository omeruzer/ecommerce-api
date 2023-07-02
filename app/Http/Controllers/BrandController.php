<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class BrandController extends Controller
{
    public function all()
    {
        $brands = Brand::orderBy('name','ASC')->paginate(10);

        return response()->json($brands);
    }
    public function detail($id)
    {
        $brands = Brand::with('products')->find($id);

        if (!$brands) {
            return response()->json(['data' => 'Brand not found!', 'status' => 404], 404);
        }

        return response()->json($brands);
    }
    public function create(Request $request)
    {
        $rules = [
            'name' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()],400);
        } else {
            $brand = Brand::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return response()->json($brand);
        }
    }
    public function edit(Request $request, $id)
    {
        $rules = [
            'name' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()],400);
        } else {
            $brand = Brand::find($id);

            if (!$brand) {
                return response()->json(['data' => 'Brand not found!', 'status' => 404], 404);
            }

            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);

            $brand->save();

            return response()->json(['data' => 'Updated', 'status' => 200]);
        }
    }
    public function remove($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['data' => 'Brand not found!', 'status' => 404], 404);
        }

        $brand->delete();

        return response()->json(['data' => 'Deleted', 'status' => 200]);
    }
}
