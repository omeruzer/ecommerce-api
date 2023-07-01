<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShippingStatus;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function order()
    {
        $data = [];

        $statuses = ShippingStatus::all();

        $orders = Order::count();

        $data["total"] = $orders;

        foreach ($statuses as $key => $status) {
            $order = Order::where('status_id', $status->id)->get();
            $orderCount = Order::where('status_id', $status->id)->count();

            $data["balance"][] = [
                'status_id' => $status->id,
                'status' => $status->title,
                'balance' => $order->sum('amount'),
            ];

            $data["data"][] = [
                'status_id' => $status->id,
                'status' => $status->title,
                'count' => $orderCount,
            ];
        }

        return response()->json($data);
    }
}
