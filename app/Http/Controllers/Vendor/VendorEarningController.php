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

        // Calculate stats from delivered and paid orders
        $totalStats = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->where('orders.status', 'delivered')
            ->where('orders.payment_status', 'paid')
            ->selectRaw('
                SUM(order_items.actual_item_price * order_items.quantity_requested) as total_sales,
                SUM(order_items.actual_item_price * order_items.quantity_requested * 0.10) as total_commission,
                SUM(order_items.actual_item_price * order_items.quantity_requested * 0.90) as total_earned,
                COUNT(DISTINCT orders.id) as total_orders
            ')
            ->first();

        // Current period starts from beginning of current month
        $currentPeriodStart = Carbon::now()->startOfMonth();
        $currentPeriodSales = $this->calculateCurrentPeriodSales($vendor->id, $currentPeriodStart);

        return view('vendor.earnings.index', compact(
            'currentPeriodSales',
            'totalStats',
            'currentPeriodStart'
        ));
    }

    /**
     * Calculate current period sales
     */
    private function calculateCurrentPeriodSales($vendorId, $startDate)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->where('orders.status', 'delivered')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('
                COUNT(DISTINCT orders.id) as order_count,
                SUM(order_items.actual_item_price * order_items.quantity_requested) as total_sales,
                SUM(order_items.actual_item_price * order_items.quantity_requested * 0.10) as estimated_commission
            ')
            ->first();
    }
}
