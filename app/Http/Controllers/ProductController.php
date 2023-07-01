<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class ProductController extends Controller
{
    public function all()
    {
        $products = Product::with('brand', 'category')->paginate(10);

        return response()->json($products);
    }
    public function detail($id)
    {
        $products = Product::with('brand', 'category', 'comments.user', 'images')->find($id);

        if (!$products) {
            return response()->json(['data' => 'Product not found!', 'status' => 404], 404);
        }

        return response()->json($products);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'desc' => ['required'],
            'price' => ['required'],
            'category_id' => ['required'],
            'brand_id' => ['required'],
            'code' => ['required', 'unique:products'],
            'description' => ['required'],
            'keywords' => ['required'],
            'quantity' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 401, "errors" => $validator->errors()]);
        } else {
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'price' => $request->price,
                'desc' => $request->desc,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'code' => $request->code,
                'description' => $request->description,
                'keywords' => $request->keywords,
                'quantity' => $request->quantity,
            ]);

            return response()->json($product);
        }
    }

    public function edit(Request $request, $id)
    {
        $rules = [
            'name' => ['required'],
            'desc' => ['required'],
            'price' => ['required'],
            'category_id' => ['required'],
            'brand_id' => ['required'],
            'code' => ['unique:products'],
            'description' => ['required'],
            'keywords' => ['required'],
            'quantity' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 401, "errors" => $validator->errors()]);
        } else {
            $data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'desc' => $request->desc,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'description' => $request->description,
                'keywords' => $request->keywords,
                'quantity' => $request->quantity,
            ];

            if ($request->email) {
                $data['code'] = $request->code;
            }

            $product = Product::find($id);

            if (!$product) {
                return response()->json(['data' => 'Product not found!', 'status' => 404], 404);
            }

            $product->update($data);

            return response()->json(['status' => true, 'message' => 'Updated']);
        }
    }

    public function remove($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['data' => 'Product not found!', 'status' => 404], 404);
        }

        $product->delete();

        return response()->json(['data' => 'Deleted', 'status' => 200]);
    }
}
