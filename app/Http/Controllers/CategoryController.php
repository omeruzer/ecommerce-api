<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class CategoryController extends Controller
{
    public function all()
    {
        $categories = Category::with('subCategories')->whereNull('parent_id')->paginate(10);

        return response()->json($categories);
    }

    public function detail($id)
    {
        $categories = Category::with('subCategories')->whereNull('parent_id')->with('products')->find($id);

        if (!$categories) {
            return response()->json(['data' => 'Category not found!', 'status' => 404], 404);
        }

        return response()->json($categories);
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
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);

            return response()->json($category);
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
            $category = Category::find($id);

            if (!$category) {
                return response()->json(['data' => 'Category not found!', 'status' => 404], 404);
            }

            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            if ($request->parent_id) {
                $category->parent_id = $request->parent_id;
            }

            $category->save();

            return response()->json(['data' => 'Updated', 'status' => 200]);
        }
    }
    public function remove($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['data' => 'Category not found!', 'status' => 404], 404);
        }

        if (!$category->parent_id) {
            $subCategories = Category::where('parent_id', $id)->get();

            foreach ($subCategories as $key => $subCategory) {
                $sub  = Category::find($subCategory->id);
                $sub->delete();
            }
        }

        $category->delete();

        return response()->json(['data' => 'Deleted', 'status' => 200]);
    }
}
