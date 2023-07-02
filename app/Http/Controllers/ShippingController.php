<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        $rules = [
            'price' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $price = Shipping::first();
            $price->price = $request->price;
            $price->save();

            return response()->json(["status" => 200, "data" => 'Updated'], 200);
        }
    }
}
