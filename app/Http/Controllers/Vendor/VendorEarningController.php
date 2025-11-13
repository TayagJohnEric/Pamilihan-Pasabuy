<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VendorEarningController extends Controller
{
     /**
     * Display vendor earnings dashboard
     */
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // Calculate stats from paid orders (lifetime)
        $totalStats = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->where(function ($q) {
                $q->where('orders.payment_status', 'paid')
                  ->orWhere(function ($q2) {
                      $q2->where('orders.payment_method', 'cod')
                         ->where('orders.status', 'delivered');
                  });
            })
            ->selectRaw('
                SUM(COALESCE(order_items.actual_item_price, order_items.customer_budget_requested, order_items.unit_price_snapshot * order_items.quantity_requested)) as total_sales,
                SUM(COALESCE(order_items.actual_item_price, order_items.customer_budget_requested, order_items.unit_price_snapshot * order_items.quantity_requested) * 0.10) as total_commission,
                SUM(COALESCE(order_items.actual_item_price, order_items.customer_budget_requested, order_items.unit_price_snapshot * order_items.quantity_requested) * 0.90) as total_earned,
                COUNT(DISTINCT orders.id) as total_orders
            ')
            ->first();

        // Weekly and Monthly period stats
        $weeklyStart = Carbon::now()->startOfWeek();
        $monthlyStart = Carbon::now()->startOfMonth();

        $weeklyStats = $this->calculatePeriodStats($vendor->id, $weeklyStart);
        $monthlyStats = $this->calculatePeriodStats($vendor->id, $monthlyStart);

        // Provide safe defaults to avoid null property access in the view
        if (!$totalStats) {
            $totalStats = (object) [
                'total_sales' => 0,
                'total_commission' => 0,
                'total_earned' => 0,
                'total_orders' => 0,
            ];
        }

        if (!$weeklyStats) {
            $weeklyStats = (object) [
                'order_count' => 0,
                'gross_sales' => 0,
                'net_earned' => 0,
            ];
        }

        if (!$monthlyStats) {
            $monthlyStats = (object) [
                'order_count' => 0,
                'gross_sales' => 0,
                'net_earned' => 0,
            ];
        }

        // Insights
        $avgEarningsPerOrder = ($totalStats && $totalStats->total_orders)
            ? ($totalStats->total_earned / $totalStats->total_orders)
            : 0;

        // Recent earnings (last 5 paid orders for this vendor)
        $recentEarnings = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->where(function ($q) {
                $q->where('orders.payment_status', 'paid')
                  ->orWhere(function ($q2) {
                      $q2->where('orders.payment_method', 'cod')
                         ->where('orders.status', 'delivered');
                  });
            })
            ->selectRaw('
                orders.id as order_id,
                orders.created_at as ordered_at,
                SUM(COALESCE(order_items.actual_item_price, order_items.customer_budget_requested, order_items.unit_price_snapshot * order_items.quantity_requested) * 0.90) as net_earned,
                SUM(order_items.quantity_requested) as items_count
            ')
            ->groupBy('orders.id', 'orders.created_at')
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('vendor.earnings.index', compact(
            'totalStats',
            'weeklyStats',
            'monthlyStats',
            'avgEarningsPerOrder',
            'recentEarnings'
        ));
    }

    /**
     * Calculate stats within a period [startDate, now]
     */
    private function calculatePeriodStats($vendorId, $startDate)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->where(function ($q) {
                $q->where('orders.payment_status', 'paid')
                  ->orWhere(function ($q2) {
                      $q2->where('orders.payment_method', 'cod')
                         ->where('orders.status', 'delivered');
                  });
            })
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('
                COUNT(DISTINCT orders.id) as order_count,
                SUM(COALESCE(order_items.actual_item_price, order_items.customer_budget_requested, order_items.unit_price_snapshot * order_items.quantity_requested)) as gross_sales,
                SUM(COALESCE(order_items.actual_item_price, order_items.customer_budget_requested, order_items.unit_price_snapshot * order_items.quantity_requested) * 0.90) as net_earned
            ')
            ->first();
    }
}
