<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('user', 'products.product')->where('user_id', Auth::id())->first();

        if (!$cart) {
            return response()->json(['data' => 'Cart not found!', 'status' => 404], 404);
        }

        return response()->json($cart);
    }
    public function action(Request $request)
    {
        $rules = [
            'action' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $cart = Cart::where('user_id', Auth::id())->first();

            if (!$cart) {
                return response()->json(["status" => 404, "data" => "Cart not found!"], 404);
            }

            $action = $request->action;

            switch ($action) {
                case 'increase':
                    $cartProduct = CartProducts::where('cart_id', $cart->id)->where('product_id', $request->product_id)->first();

                    if (!$cartProduct) {
                        CartProducts::create([
                            'cart_id' => $cart->id,
                            'product_id' => $request->product_id,
                            'quantity' => 1
                        ]);
                    } else {
                        $cartProduct->quantity += 1;
                        $cartProduct->save();
                    }
                    return response()->json(["status" => 200, "data" => "Product added to cart"], 200);
                    break;
                case 'decrease':
                    $cartProduct = CartProducts::where('cart_id', $cart->id)->where('product_id', $request->product_id)->first();

                    if (!$cartProduct) {
                        return response()->json(["status" => 404, "data" => "Product not found"], 404);
                    }

                    $cartProduct->quantity -= 1;
                    $quantity = $cartProduct->quantity;

                    if ($quantity == 0) {
                        $cartProduct->delete();
                    } else {
                        $cartProduct->save();
                    }
                    return response()->json(["status" => 200, "data" => "Product decreased"], 200);
                    break;
                case 'delete_product':
                    $cartProduct = CartProducts::where('cart_id', $cart->id)->where('product_id', $request->product_id)->first();

                    if (!$cartProduct) {
                        return response()->json(["status" => 404, "data" => "Product not found"], 404);
                    }

                    $cartProduct->delete();

                    return response()->json(["status" => 200, "data" => "Product deleted"], 200);
                    break;
                case 'delete':
                    $cart->delete();

                    return response()->json(["status" => 200, "data" => "Cart deleted"], 200);
                    break;

                default:
                    return response()->json(["status" => 400, "data" => "invalid command"], 400);
                    break;
            }
        }
    }
}
