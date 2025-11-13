<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Notification;
use App\Models\VendorPayout;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorDashboardController extends Controller
{
  /**
     * Display the vendor dashboard with comprehensive business statistics and order management
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the authenticated vendor
        $user = Auth::user();
        $vendor = $user->vendor;

        // If vendor profile doesn't exist, redirect to profile setup
        if (!$vendor) {
            return redirect()->route('vendor.profile.setup')
                ->with('error', 'Please complete your vendor profile first.');
        }

        // Get dashboard statistics
        $dashboardStats = $this->getDashboardStatistics($vendor, $user);

        return view('vendor.dashboard.index', compact('dashboardStats'));
    }

    /**
     * Get comprehensive dashboard statistics for the vendor
     * 
     * @param \App\Models\Vendor $vendor
     * @param \App\Models\User $user
     * @return array
     */
    private function getDashboardStatistics($vendor, $user)
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            // Basic vendor info
            'vendor_info' => [
                'name' => $user->first_name . ' ' . $user->last_name,
                'shop_name' => $vendor->vendor_name,
                'rating' => $vendor->average_rating ?? 0,
                'verification_status' => $vendor->verification_status,
                'is_accepting_orders' => $vendor->is_accepting_orders,
                'stall_number' => $vendor->stall_number,
                'market_section' => $vendor->market_section,
                'business_hours' => $vendor->business_hours,
            ],

            // Today's statistics
            'today_stats' => [
                'sales_amount' => $this->getTodaySales($vendor->id),
                'orders_count' => $this->getTodayOrders($vendor->id),
                'pending_orders' => $this->getTodayPendingOrders($vendor->id),
                'completed_orders' => $this->getTodayCompletedOrders($vendor->id),
                'items_sold' => $this->getTodayItemsSold($vendor->id),
            ],

            // Weekly statistics
            'weekly_stats' => [
                'sales_amount' => $this->getWeeklySales($vendor->id),
                'orders_count' => $this->getWeeklyOrders($vendor->id),
                'average_order_value' => $this->getWeeklyAverageOrderValue($vendor->id),
                'customer_rating' => $this->getWeeklyAverageRating($vendor->id),
            ],

            // Monthly statistics
            'monthly_stats' => [
                'sales_amount' => $this->getMonthlySales($vendor->id),
                'orders_count' => $this->getMonthlyOrders($vendor->id),
                'unique_customers' => $this->getMonthlyUniqueCustomers($vendor->id),
                'fulfillment_rate' => $this->getMonthlyFulfillmentRate($vendor->id),
            ],

            // Pending orders requiring attention
            'pending_orders' => $this->getPendingOrders($vendor->id),

            // Recent activity
            'recent_activity' => $this->getRecentActivity($vendor->id),

            // Product performance
            'product_stats' => [
                'total_products' => $this->getTotalProducts($vendor->id),
                'active_products' => $this->getActiveProducts($vendor->id),
                'out_of_stock' => $this->getOutOfStockProducts($vendor->id),
                'low_stock' => $this->getLowStockProducts($vendor->id),
            ],

            // Financial overview
            'financial_overview' => [
                'unsettled_earnings' => $this->getUnsettledEarnings($user->id),
                'last_payout' => $this->getLastPayout($user->id),
                'total_lifetime_sales' => $this->getTotalLifetimeSales($vendor->id),
                'pending_payout_amount' => $this->getPendingPayoutAmount($user->id),
            ],

            // Recent notifications
            'recent_notifications' => $this->getRecentNotifications($user->id),

            // Top selling products
            'top_products' => $this->getTopSellingProducts($vendor->id),

            // Order status breakdown
            'order_status_breakdown' => $this->getOrderStatusBreakdown($vendor->id),
        ];
    }

    /**
     * Get today's total sales amount for the vendor
     * 
     * @param int $vendorId
     * @return float
     */
    private function getTodaySales($vendorId)
    {
        return OrderItem::whereHas('order', function ($query) {
                $query->whereDate('created_at', Carbon::today())
                      ->where('payment_status', 'paid');
            })
            ->whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->sum(DB::raw('COALESCE(actual_item_price, unit_price_snapshot * quantity_requested)')) ?? 0;
    }

    /**
     * Get today's order count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getTodayOrders($vendorId)
    {
        return Order::whereDate('created_at', Carbon::today())
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->distinct()
            ->count();
    }

    /**
     * Get today's pending orders count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getTodayPendingOrders($vendorId)
    {
        return Order::whereDate('created_at', Carbon::today())
            ->whereIn('status', ['processing', 'awaiting_rider_assignment'])
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->distinct()
            ->count();
    }

    /**
     * Get today's completed orders count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getTodayCompletedOrders($vendorId)
    {
        return Order::whereDate('updated_at', Carbon::today())
            ->where('status', 'delivered')
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->distinct()
            ->count();
    }

    /**
     * Get today's items sold count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getTodayItemsSold($vendorId)
    {
        return OrderItem::whereHas('order', function ($query) {
                $query->whereDate('created_at', Carbon::today())
                      ->where('payment_status', 'paid');
            })
            ->whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->sum('quantity_requested') ?? 0;
    }

    /**
     * Get weekly sales amount
     * 
     * @param int $vendorId
     * @return float
     */
    private function getWeeklySales($vendorId)
    {
        return OrderItem::whereHas('order', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->startOfWeek())
                      ->where('payment_status', 'paid');
            })
            ->whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->sum(DB::raw('COALESCE(actual_item_price, unit_price_snapshot * quantity_requested)')) ?? 0;
    }

    /**
     * Get weekly orders count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getWeeklyOrders($vendorId)
    {
        return Order::where('created_at', '>=', Carbon::now()->startOfWeek())
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->distinct()
            ->count();
    }

    /**
     * Get weekly average order value
     * 
     * @param int $vendorId
     * @return float
     */
    private function getWeeklyAverageOrderValue($vendorId)
    {
        $totalSales = $this->getWeeklySales($vendorId);
        $orderCount = $this->getWeeklyOrders($vendorId);
        
        return $orderCount > 0 ? $totalSales / $orderCount : 0;
    }

    /**
     * Get weekly average rating
     * 
     * @param int $vendorId
     * @return float
     */
    private function getWeeklyAverageRating($vendorId)
    {
        $vendor = Vendor::find($vendorId);
        return $vendor->ratingsReceived()
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->avg('rating_value') ?? 0;
    }

    /**
     * Get monthly sales amount
     * 
     * @param int $vendorId
     * @return float
     */
    private function getMonthlySales($vendorId)
    {
        return OrderItem::whereHas('order', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->startOfMonth())
                      ->where('payment_status', 'paid');
            })
            ->whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->sum(DB::raw('COALESCE(actual_item_price, unit_price_snapshot * quantity_requested)')) ?? 0;
    }

    /**
     * Get monthly orders count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getMonthlyOrders($vendorId)
    {
        return Order::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->distinct()
            ->count();
    }

    /**
     * Get monthly unique customers count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getMonthlyUniqueCustomers($vendorId)
    {
        return Order::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->distinct('customer_user_id')
            ->count();
    }

    /**
     * Get monthly fulfillment rate
     * 
     * @param int $vendorId
     * @return float
     */
    private function getMonthlyFulfillmentRate($vendorId)
    {
        $totalOrders = $this->getMonthlyOrders($vendorId);
        $completedOrders = Order::where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('status', 'delivered')
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->distinct()
            ->count();
            
        return $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
    }

    /**
     * Get pending orders requiring vendor attention
     * 
     * @param int $vendorId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPendingOrders($vendorId)
    {
        return Order::whereIn('status', ['processing', 'awaiting_rider_assignment'])
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->with(['customer', 'orderItems.product', 'deliveryAddress'])
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'status' => $order->status,
                    'items_count' => $order->orderItems->count(),
                    'total_amount' => $order->orderItems->sum('actual_item_price') ?: $order->orderItems->sum('unit_price_snapshot'),
                    'created_at' => $order->created_at->format('M d, Y H:i'),
                    'time_ago' => $order->created_at->diffForHumans(),
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                ];
            });
    }

    /**
     * Get recent activity for the vendor
     * 
     * @param int $vendorId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentActivity($vendorId)
    {
        return Order::whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->with(['customer', 'orderItems'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'status' => $order->status,
                    'items_count' => $order->orderItems->count(),
                    'total_amount' => $order->orderItems->sum('actual_item_price') ?: $order->orderItems->sum('unit_price_snapshot'),
                    'time' => $order->updated_at->diffForHumans(),
                    'updated_at' => $order->updated_at->format('M d, Y H:i'),
                ];
            });
    }

    /**
     * Get total products count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getTotalProducts($vendorId)
    {
        return Product::where('vendor_id', $vendorId)->count();
    }

    /**
     * Get active products count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getActiveProducts($vendorId)
    {
        return Product::where('vendor_id', $vendorId)
            ->where('is_available', true)
            ->count();
    }

    /**
     * Get out of stock products count
     * 
     * @param int $vendorId
     * @return int
     */
    private function getOutOfStockProducts($vendorId)
    {
        return Product::where('vendor_id', $vendorId)
            ->where('quantity_in_stock', 0)
            ->count();
    }

    /**
     * Get low stock products count (less than 10 units)
     * 
     * @param int $vendorId
     * @return int
     */
    private function getLowStockProducts($vendorId)
    {
        return Product::where('vendor_id', $vendorId)
            ->where('quantity_in_stock', '>', 0)
            ->where('quantity_in_stock', '<=', 10)
            ->count();
    }

    /**
     * Get unsettled earnings amount
     * 
     * @param int $userId
     * @return float
     */
    private function getUnsettledEarnings($userId)
    {
        return VendorPayout::where('vendor_user_id', $userId)
            ->whereIn('status', ['pending_calculation', 'pending_payment'])
            ->sum('total_payout_amount') ?? 0;
    }

    /**
     * Get last payout information
     * 
     * @param int $userId
     * @return array|null
     */
    private function getLastPayout($userId)
    {
        $lastPayout = VendorPayout::where('vendor_user_id', $userId)
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->first();

        return $lastPayout ? [
            'amount' => $lastPayout->total_payout_amount,
            'date' => $lastPayout->paid_at->format('M d, Y'),
            'period' => $lastPayout->payout_period_start_date->format('M d') . ' - ' . 
                       $lastPayout->payout_period_end_date->format('M d, Y'),
        ] : null;
    }

    /**
     * Get total lifetime sales
     * 
     * @param int $vendorId
     * @return float
     */
    private function getTotalLifetimeSales($vendorId)
    {
        return OrderItem::whereHas('order', function ($query) {
                $query->where('payment_status', 'paid');
            })
            ->whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->sum(DB::raw('COALESCE(actual_item_price, unit_price_snapshot * quantity_requested)')) ?? 0;
    }

    /**
     * Get pending payout amount
     * 
     * @param int $userId
     * @return float
     */
    private function getPendingPayoutAmount($userId)
    {
        return VendorPayout::where('vendor_user_id', $userId)
            ->where('status', 'pending_payment')
            ->sum('total_payout_amount') ?? 0;
    }

    /**
     * Get recent notifications for the vendor
     * 
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentNotifications($userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => is_array($notification->message) ? 
                        $notification->message['text'] ?? 'New notification' : 
                        $notification->message,
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                    'type' => $notification->type,
                ];
            });
    }

    /**
     * Get top selling products
     * 
     * @param int $vendorId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTopSellingProducts($vendorId)
    {
        return Product::where('vendor_id', $vendorId)
            ->withCount(['orderItems as sales_count' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                });
            }])
            ->orderBy('sales_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'sales_count' => $product->sales_count,
                    'price' => $product->price,
                    'stock' => $product->quantity_in_stock,
                    'image' => $product->product_image_url,
                ];
            });
    }

    /**
     * Get order status breakdown for current week
     * 
     * @param int $vendorId
     * @return array
     */
    private function getOrderStatusBreakdown($vendorId)
    {
        $statusCounts = Order::where('created_at', '>=', Carbon::now()->startOfWeek())
            ->whereHas('orderItems.product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'processing' => $statusCounts['processing'] ?? 0,
            'awaiting_rider_assignment' => $statusCounts['awaiting_rider_assignment'] ?? 0,
            'assigned' => $statusCounts['assigned'] ?? 0,
            'out_for_delivery' => $statusCounts['out_for_delivery'] ?? 0,
            'delivered' => $statusCounts['delivered'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0,
        ];
    }

    /**
     * Toggle vendor's order acceptance status
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleOrderAcceptance(Request $request)
    {
        $user = Auth::user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json(['error' => 'Vendor profile not found'], 404);
        }

        // Toggle order acceptance status
        $vendor->is_accepting_orders = !$vendor->is_accepting_orders;
        $vendor->save();

        return response()->json([
            'success' => true,
            'is_accepting_orders' => $vendor->is_accepting_orders,
            'message' => $vendor->is_accepting_orders ? 
                'You are now accepting orders' : 
                'You have stopped accepting new orders'
        ]);
    }
}
