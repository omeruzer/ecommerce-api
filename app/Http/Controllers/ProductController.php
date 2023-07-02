<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Str;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $data = [];

        $products = Product::with('brand', 'category');

        if ($request->has('search')) {
            $products->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $products->where('category_id', $request->category);
        }

        if ($request->has('brand')) {
            $products->where('brand_id', $request->brand);
        }

        if ($request->has('sortBy')) {
            if ($request->sortBy == 'a-z') {
                $products->orderBy('name', 'ASC');
            } else if ($request->sortBy == 'z-a') {
                $products->orderBy('name', 'DESC');
            } else if ($request->sortBy == 'cheap') {
                $products->orderBy('price', 'ASC');
            } else if ($request->sortBy == 'expensive') {
                $products->orderBy('price', 'DESC');
            } else if ($request->sortBy == 'first') {
                $products->orderBy('created_at', 'ASC');
            } else if ($request->sortBy == 'last') {
                $products->orderBy('created_at', 'DESC');
            }
        }

        $data = $products->paginate(10);

        return response()->json($data);
    }

    public function detail($id)
    {
        $products = Product::with('brand', 'category', 'comments.user')->find($id);

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
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
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

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

                Storage::disk('public')->put("media/products/" . $fileName, file_get_contents($image));

                $product->image = $fileName;

                $product->save();
            }


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
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
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

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

                Storage::disk('public')->put("media/products/" . $fileName, file_get_contents($image));

                if ($product->image) {
                    Storage::disk('public')->delete("media/products/" . $product->image);
                }

                $product->image = $fileName;

                $product->save();
            }

            return response()->json(['status' => true, 'message' => 'Updated']);
        }
    }

    public function remove($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['data' => 'Product not found!', 'status' => 404], 404);
        }
        if ($product->image) {
            Storage::disk('public')->delete("media/products/" . $product->image);
        }

        $product->delete();

        return response()->json(['data' => 'Deleted', 'status' => 200]);
    }
}
