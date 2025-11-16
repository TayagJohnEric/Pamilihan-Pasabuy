<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiderDashboardController extends Controller
{
    
    /**
     * Display the rider dashboard with comprehensive statistics and recent activity
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the authenticated rider
        $user = Auth::user();
        $rider = $user->rider;

        // If rider profile doesn't exist, redirect to profile setup
        if (!$rider) {
            return redirect()->route('rider.profile.setup')
                ->with('error', 'Please complete your rider profile first.');
        }

        // Get dashboard statistics
        $dashboardStats = $this->getDashboardStatistics($rider, $user);

        return view('rider.dashboard.dashboard', compact('dashboardStats'));
    }

    /**
     * Get comprehensive dashboard statistics for the rider
     * 
     * @param \App\Models\Rider $rider
     * @param \App\Models\User $user
     * @return array
     */
    private function getDashboardStatistics($rider, $user)
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            // Basic rider info
            'rider_info' => [
                'name' => $user->first_name . ' ' . $user->last_name,
                'rating' => $rider->average_rating ?? 0,
                'verification_status' => $rider->verification_status,
                'is_available' => $rider->is_available,
                'vehicle_type' => $rider->vehicle_type ?? 'Not specified',
            ],

            // Today's statistics
            'today_stats' => [
                'total_deliveries' => $this->getTodayDeliveries($user->id),
                'completed_orders' => $this->getTodayCompletedOrders($user->id),
                'earnings' => $this->getTodayEarnings($user->id),
                'distance_covered' => $this->getEstimatedDistance($user->id, $today),
            ],

            // Weekly statistics
            'weekly_stats' => [
                'total_deliveries' => $this->getWeeklyDeliveries($user->id),
                'completed_orders' => $this->getWeeklyCompletedOrders($user->id),
                'earnings' => $this->getWeeklyEarnings($user->id),
                'average_rating' => $this->getWeeklyAverageRating($user->id),
            ],

            // Monthly statistics
            'monthly_stats' => [
                'total_deliveries' => $this->getMonthlyDeliveries($user->id),
                'completed_orders' => $this->getMonthlyCompletedOrders($user->id),
                'earnings' => $this->getMonthlyEarnings($user->id),
                'success_rate' => $this->getMonthlySuccessRate($user->id),
            ],

            // Recent activity
            'recent_activity' => $this->getRecentActivity($user->id),

            // Current orders
            'current_orders' => $this->getCurrentOrders($user->id),

            // Notifications
            'recent_notifications' => $this->getRecentNotifications($user->id),

            // Performance metrics
            'performance' => [
                'total_lifetime_deliveries' => $rider->total_deliveries,
                'completion_rate' => $this->getCompletionRate($user->id),
                'average_delivery_time' => $this->getAverageDeliveryTime($user->id),
                'customer_satisfaction' => $rider->average_rating ?? 0,
            ],

            // Rider rank based on merit system
            'rider_rank' => $this->getRiderRank($rider->id, $user->id),
        ];
    }

    /**
     * Get today's delivery count for the rider
     * 
     * @param int $riderId
     * @return int
     */
    private function getTodayDeliveries($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->whereDate('created_at', Carbon::today())
            ->count();
    }

    /**
     * Get today's completed orders count
     * 
     * @param int $riderId
     * @return int
     */
    private function getTodayCompletedOrders($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('status', 'delivered')
            ->whereDate('updated_at', Carbon::today())
            ->count();
    }

    /**
     * Get today's estimated earnings (delivery fees from completed orders)
     * 
     * @param int $riderId
     * @return float
     */
    private function getTodayEarnings($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('status', 'delivered')
            ->whereDate('updated_at', Carbon::today())
            ->sum('delivery_fee') ?? 0;
    }

    /**
     * Get estimated distance covered today (placeholder calculation)
     * 
     * @param int $riderId
     * @param Carbon $date
     * @return string
     */
    private function getEstimatedDistance($riderId, $date)
    {
        // Simple estimation: 3km average per delivery
        $deliveries = $this->getTodayCompletedOrders($riderId);
        $estimatedKm = $deliveries * 3;
        return $estimatedKm . ' km';
    }

    /**
     * Get weekly delivery count
     * 
     * @param int $riderId
     * @return int
     */
    private function getWeeklyDeliveries($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->count();
    }

    /**
     * Get weekly completed orders count
     * 
     * @param int $riderId
     * @return int
     */
    private function getWeeklyCompletedOrders($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('status', 'delivered')
            ->where('updated_at', '>=', Carbon::now()->startOfWeek())
            ->count();
    }

    /**
     * Get weekly earnings
     * 
     * @param int $riderId
     * @return float
     */
    private function getWeeklyEarnings($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('status', 'delivered')
            ->where('updated_at', '>=', Carbon::now()->startOfWeek())
            ->sum('delivery_fee') ?? 0;
    }

    /**
     * Get weekly average rating
     * 
     * @param int $riderId
     * @return float
     */
    private function getWeeklyAverageRating($riderId)
    {
        return $riderId ? Auth::user()->ratingsReceived()
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->avg('rating_value') ?? 0 : 0;
    }

    /**
     * Get monthly deliveries count
     * 
     * @param int $riderId
     * @return int
     */
    private function getMonthlyDeliveries($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
    }

    /**
     * Get monthly completed orders
     * 
     * @param int $riderId
     * @return int
     */
    private function getMonthlyCompletedOrders($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('status', 'delivered')
            ->where('updated_at', '>=', Carbon::now()->startOfMonth())
            ->count();
    }

    /**
     * Get monthly earnings
     * 
     * @param int $riderId
     * @return float
     */
    private function getMonthlyEarnings($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->where('status', 'delivered')
            ->where('updated_at', '>=', Carbon::now()->startOfMonth())
            ->sum('delivery_fee') ?? 0;
    }

    /**
     * Get monthly success rate (completed vs total orders)
     * 
     * @param int $riderId
     * @return float
     */
    private function getMonthlySuccessRate($riderId)
    {
        $totalOrders = $this->getMonthlyDeliveries($riderId);
        $completedOrders = $this->getMonthlyCompletedOrders($riderId);
        
        return $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
    }

    /**
     * Get recent activity for the rider (last 10 activities)
     * 
     * @param int $riderId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentActivity($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->with(['customer', 'deliveryAddress'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'status' => $order->status,
                    'amount' => $order->final_total_amount,
                    'delivery_fee' => $order->delivery_fee,
                    'time' => $order->updated_at->diffForHumans(),
                    'address' => $order->deliveryAddress->address_line_1 ?? 'Address not available',
                ];
            });
    }

    /**
     * Get current active orders for the rider
     * 
     * @param int $riderId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getCurrentOrders($riderId)
    {
        return Order::where('rider_user_id', $riderId)
            ->whereIn('status', ['assigned', 'pickup_confirmed', 'out_for_delivery'])
            ->with(['customer', 'deliveryAddress'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'status' => $order->status,
                    'amount' => $order->final_total_amount,
                    'delivery_fee' => $order->delivery_fee,
                    'created_at' => $order->created_at->format('M d, Y H:i'),
                    'address' => $order->deliveryAddress->address_line_1 ?? 'Address not available',
                    'special_instructions' => $order->special_instructions,
                ];
            });
    }

    /**
     * Get recent notifications for the rider
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
     * Get overall completion rate for the rider
     * 
     * @param int $riderId
     * @return float
     */
    private function getCompletionRate($riderId)
    {
        $totalOrders = Order::where('rider_user_id', $riderId)->count();
        $completedOrders = Order::where('rider_user_id', $riderId)
            ->where('status', 'delivered')
            ->count();
        
        return $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
    }

    /**
     * Get average delivery time (placeholder - would need delivery timestamps)
     * 
     * @param int $riderId
     * @return string
     */
    private function getAverageDeliveryTime($riderId)
    {
        // This would require pickup_time and delivery_time fields
        // For now, return a placeholder
        return '25 mins';
    }

    /**
     * Get rider's rank based on merit system
     * Merit Score = (average_rating * 0.6) + (log(total_deliveries + 1) * 0.3) + (comment_count * 0.1)
     * Only returns rank if rider is in top 3
     * 
     * @param int $riderId
     * @param int $userId
     * @return array|null
     */
    private function getRiderRank($riderId, $userId)
    {
        // Get all riders with merit scores, same logic as CustomerMeritSystemController
        $riders = DB::table('riders as r')
            ->join('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('ratings as rat', function($join) {
                $join->on('rat.rateable_id', '=', 'r.user_id')
                     ->where('rat.rateable_type', '=', 'App\\Models\\User');
            })
            ->select([
                'r.id',
                'r.user_id',
                'u.first_name',
                'u.last_name',
                'r.average_rating',
                'r.total_deliveries',
                DB::raw('COUNT(CASE WHEN rat.comment IS NOT NULL AND rat.comment != "" THEN 1 END) as comment_count'),
                DB::raw('(
                    COALESCE(r.average_rating, 0) * 0.6 + 
                    LOG(r.total_deliveries + 1) * 0.3 + 
                    COUNT(CASE WHEN rat.comment IS NOT NULL AND rat.comment != "" THEN 1 END) * 0.1
                ) as merit_score')
            ])
            ->where('r.verification_status', 'verified')
            ->where('u.is_active', true)
            ->groupBy('r.id', 'r.user_id', 'u.first_name', 'u.last_name', 'r.average_rating', 'r.total_deliveries')
            ->orderBy('merit_score', 'desc')
            ->get();

        // Find current rider's position
        $rank = null;
        foreach ($riders as $index => $rider) {
            if ($rider->id == $riderId) {
                $rank = $index + 1; // 1-indexed rank
                break;
            }
        }

        // Only return rank data if rider is in top 3
        if ($rank && $rank <= 3) {
            return [
                'rank' => $rank,
                'merit_score' => $riders[$rank - 1]->merit_score ?? 0,
                'total_riders' => $riders->count(),
            ];
        }

        return null;
    }

    /**
     * Toggle rider availability status
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();
        $rider = $user->rider;

        if (!$rider) {
            return response()->json(['error' => 'Rider profile not found'], 404);
        }

        // Toggle availability status
        $rider->is_available = !$rider->is_available;
        $rider->save();

        return response()->json([
            'success' => true,
            'is_available' => $rider->is_available,
            'message' => $rider->is_available ? 
                'You are now available for deliveries' : 
                'You are now offline'
        ]);
    }
}
