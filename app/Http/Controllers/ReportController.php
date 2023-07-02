<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShippingStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function index()
    {
        $data = [];
        $date = Carbon::today();

        $today = Order::whereDate('created_at', $date)->sum('amount');
        $todayCount = Order::whereDate('created_at', $date)->count();
        $todayPending = Order::whereDate('created_at', $date)->where('status_id', 1)->sum('amount');
        $todayPendingCount = Order::whereDate('created_at', $date)->where('status_id', 1)->count();
        $todayShipped = Order::whereDate('created_at', $date)->where('status_id', 3)->sum('amount');
        $todayShippedCount = Order::whereDate('created_at', $date)->where('status_id', 3)->count();
        $todayCompleted = Order::whereDate('created_at', $date)->where('status_id', 8)->sum('amount');
        $todayCompletedCount = Order::whereDate('created_at', $date)->where('status_id', 8)->count();

        $data['today-total-balance'] = $today;
        $data['today-total-count'] = $todayCount;
        $data['today-pending-balance'] = $todayPending;
        $data['today-pending-count'] = $todayPendingCount;
        $data['today-shipped-balance'] = $todayShipped;
        $data['today-shipped-count'] = $todayShippedCount;
        $data['today-completed-balance'] = $todayCompleted;
        $data['today-completed-count'] = $todayCompletedCount;

        return response()->json($data);
    }

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
