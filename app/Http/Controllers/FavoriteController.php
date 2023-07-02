<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    public function index()
    {
        $data = Favorite::with('product')->where('user_id', Auth::id())->orderByDesc('id')->paginate(10);

        return response()->json($data);
    }
    public function create(Request $request)
    {
        $rules = [
            'product_id' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $product = Product::find($request->product_id);

            if (!$product) {
                return response()->json(['data' => 'Product not found!', 'status' => 404], 404);
            }

            $data = Favorite::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

            if ($data) {
                $data->delete();
            } else {
                Favorite::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                ]);
            }

            return response()->json(['data' => 'Success!', 'status' => 200], 200);
        }
    }
}
