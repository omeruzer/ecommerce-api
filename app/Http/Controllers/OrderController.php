<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderMail;
use App\Mail\OrderStatusMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Shipping;
use App\Models\ShippingStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\Token;

class OrderController extends Controller
{
    public function all()
    {
        $orders = Order::with('products.product', 'status')->paginate(10);

        return response()->json($orders);
    }
    public function index()
    {
        $orders = Order::with('products.product', 'status')->where('user_id', Auth::id())->paginate(10);

        return response()->json($orders);
    }
    public function detail($id)
    {
        $order = Order::with('products.product', 'status')->find($id);

        if (!$order) {
            return response()->json(['status' => 404, 'data' => 'Order not found'], 404);
        }

        $order->is_view = 1;
        $order->save();

        return response()->json($order);
    }
    public function create(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'number' => ['required'],
            'exp_month' => ['required'],
            'exp_year' => ['required'],
            'cvc' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'country' => ['required'],
            'postal_code' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $shipping = Shipping::first();
            $subtotal = 0;
            $shippingPrice = $shipping->price;
            $amount = 0;

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $cart = Cart::with('products.product')->where('user_id', Auth::id())->first();
            if (!$cart) {
                return response()->json(["status" => 404, "data" => 'Cart not found'], 404);
            }

            $res = Token::create(array(
                "card" => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                ]

            ));

            foreach ($cart->products as $key => $item) {
                $quantity = $item->quantity;
                $price = $item->product->price;
                $total = $quantity * $price;
                $subtotal += $total;
            }

            $amount = $subtotal + $shippingPrice;

            $payment = Charge::create([
                "amount" => $amount * 100,
                "currency" => "usd",
                "source" => $res->id,
                "description" => $request->name . " made a shopping payment of $" . $amount,
                "shipping" => [
                    "name" => $request->name,
                    "address" => [
                        "line1" => $request->address,
                        "postal_code" => $request->postal_code,
                        "city" => $request->city,
                        "country" => $request->country,
                    ],
                ]
            ]);

            if ($payment) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => $request->country,
                    'post_code' => $request->postal_code,
                    'subtotal' => $subtotal,
                    'shipping_price' => $shippingPrice,
                    'amount' => $amount,
                    'stripe_payment_code' => $payment->id
                ]);

                foreach ($cart->products as $key => $item) {
                    OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }

                $cart->delete();

                Mail::to($request->email)->send(new NewOrderMail($order));

                return response()->json(['status' => 200, 'data' => 'Payment transaction completed'], 200);
            }

            return response()->json(['status' => 400, 'data' => 'Error'], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        $rules = [
            'status_id' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $order = Order::with('status')->find($id);

            if (!$order) {
                return response()->json(['status' => 404, 'data' => 'Order not found'], 404);
            }

            $status = ShippingStatus::find($request->status_id);

            if (!$status) {
                return response()->json(['status' => 404, 'data' => 'Status not found'], 404);
            }

            if ($request->status_id == 6 || $request->status_id == 5 || $request->status_id == 9) {
                Refund::create([
                    'charge' => $order->stripe_payment_code,
                ]);
            }

            if ($request->status_id == 3 && !$request->tracking_code) {
                return response()->json(['status' => 400, 'data' => 'Please enter the tracking code'], 400);
            }

            $order->status_id = $request->status_id;

            if ($request->status_id == 3) {
                $order->tracking_code = $request->tracking_code;
            }

            $order->save();

            $data = $status->title;

            Mail::to($order->email)->send(new OrderStatusMail($data));

            return response()->json(['status' => 200, 'data' => 'Updated'], 200);
        }
    }

    public function remove($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['status' => 404, 'data' => 'Order not found'], 404);
        }

        if ($order->status_id == 5 || $order->status_id == 6 || $order->status_id == 8 || $order->status_id == 9) {
            $order->delete();

            return response()->json(['status' => 200, 'data' => 'Deleted'], 200);
        }

        return response()->json(['status' => 400, 'data' => 'This order cannot be deleted'], 400);
    }
}
