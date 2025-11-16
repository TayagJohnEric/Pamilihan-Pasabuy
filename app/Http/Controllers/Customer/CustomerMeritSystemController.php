<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rider;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerMeritSystemController extends Controller
{
    /**
     * Display all riders ranked by their merit score
     * Merit Score = (average_rating * 0.6) + (log(total_deliveries + 1) * 0.3) + (comment_count * 0.1)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all verified riders with their merit score calculation
        $riders = $this->getRidersWithMeritScore();
        
        // Identify top 3 riders as suggested riders
        $suggestedRiders = $riders->take(3)->pluck('id')->toArray();
        
        return view('customer.merit-system.index', compact('riders', 'suggestedRiders'));
    }

    /**
     * Display detailed profile of a specific rider
     * 
     * @param int $riderId
     * @return \Illuminate\View\View
     */
    public function showRiderProfile($riderId)
    {
        // Find the rider with user information and calculate merit score
        $rider = $this->getSingleRiderWithMeritScore($riderId);
        
        if (!$rider) {
            return redirect()->route('merit-system.index')
                           ->with('error', 'Rider not found.');
        }
        
        // Get recent ratings and comments for this rider
        $recentRatings = $this->getRiderRecentRatings($riderId);
        
        // Calculate additional statistics
        $stats = $this->calculateRiderStats($riderId);
        
        return view('customer.merit-system.rider-profile', compact('rider', 'recentRatings', 'stats'));
    }

    /**
     * Get all riders with calculated merit scores, sorted by merit score descending
     * Limited to top 10 riders for the leaderboard
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getRidersWithMeritScore()
    {
        return DB::table('riders as r')
            ->join('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('ratings as rat', function($join) {
                // Ratings for riders are stored against the rider's user_id with rateable_type = App\Models\User
                $join->on('rat.rateable_id', '=', 'r.user_id')
                     ->where('rat.rateable_type', '=', 'App\\Models\\User');
            })
            ->select([
                'r.id',
                'r.user_id',
                'u.first_name',
                'u.last_name',
                'u.profile_image_url',
                'r.average_rating',
                'r.total_deliveries',
                'r.is_available',
                'r.vehicle_type',
                'r.verification_status',
                // Count comments (non-null comments only)
                DB::raw('COUNT(CASE WHEN rat.comment IS NOT NULL AND rat.comment != "" THEN 1 END) as comment_count'),
                // Calculate merit score using the provided formula
                DB::raw('(
                    COALESCE(r.average_rating, 0) * 0.6 + 
                    LOG(r.total_deliveries + 1) * 0.3 + 
                    COUNT(CASE WHEN rat.comment IS NOT NULL AND rat.comment != "" THEN 1 END) * 0.1
                ) as merit_score')
            ])
            ->where('r.verification_status', 'verified')  // Only show verified riders
            ->where('u.is_active', true)  // Only active users
            ->groupBy('r.id', 'r.user_id', 'u.first_name', 'u.last_name', 'u.profile_image_url', 
                     'r.average_rating', 'r.total_deliveries', 'r.is_available', 'r.vehicle_type', 'r.verification_status')
            ->orderBy('merit_score', 'desc')  // Highest merit score first
            ->limit(10)  // Limit to top 10 riders
            ->get();
    }

    /**
     * Get single rider with merit score calculation
     * 
     * @param int $riderId
     * @return object|null
     */
    private function getSingleRiderWithMeritScore($riderId)
    {
        return DB::table('riders as r')
            ->join('users as u', 'r.user_id', '=', 'u.id')
            ->leftJoin('ratings as rat', function($join) {
                // Ratings for riders are stored against the rider's user_id with rateable_type = App\Models\User
                $join->on('rat.rateable_id', '=', 'r.user_id')
                     ->where('rat.rateable_type', '=', 'App\\Models\\User');
            })
            ->select([
                'r.id',
                'r.user_id',
                'u.first_name',
                'u.last_name',
                'u.email',
                'u.phone_number',
                'u.profile_image_url',
                'r.average_rating',
                'r.total_deliveries',
                'r.is_available',
                'r.vehicle_type',
                'r.license_number',
                'r.verification_status',
                'r.created_at',
                DB::raw('COUNT(CASE WHEN rat.comment IS NOT NULL AND rat.comment != "" THEN 1 END) as comment_count'),
                DB::raw('(
                    COALESCE(r.average_rating, 0) * 0.6 + 
                    LOG(r.total_deliveries + 1) * 0.3 + 
                    COUNT(CASE WHEN rat.comment IS NOT NULL AND rat.comment != "" THEN 1 END) * 0.1
                ) as merit_score')
            ])
            ->where('r.id', $riderId)
            ->where('r.verification_status', 'verified')
            ->where('u.is_active', true)
            ->groupBy('r.id', 'r.user_id', 'u.first_name', 'u.last_name', 'u.email', 'u.phone_number',
                     'u.profile_image_url', 'r.average_rating', 'r.total_deliveries', 'r.is_available', 
                     'r.vehicle_type', 'r.license_number', 'r.verification_status', 'r.created_at')
            ->first();
    }

    /**
     * Get recent ratings and comments for a specific rider
     * 
     * @param int $riderId
     * @return \Illuminate\Support\Collection
     */
    private function getRiderRecentRatings($riderId)
    {
        return DB::table('ratings as rat')
            ->join('users as u', 'rat.user_id', '=', 'u.id')
            ->join('orders as o', 'rat.order_id', '=', 'o.id')
            ->select([
                'rat.rating_value',
                'rat.comment',
                'rat.created_at',
                'u.first_name as customer_first_name',
                'u.last_name as customer_last_name',
                'o.id as order_id'
            ])
            // Ratings are stored against the rider's user_id (not rider.id) with type User
            ->where('rat.rateable_id', function($query) use ($riderId) {
                $query->select('user_id')
                      ->from('riders')
                      ->where('id', $riderId);
            })
            ->where('rat.rateable_type', 'App\\Models\\User')
            ->whereNotNull('rat.comment')
            ->where('rat.comment', '!=', '')
            ->orderBy('rat.created_at', 'desc')
            ->limit(10)  // Show only recent 10 reviews
            ->get();
    }

    /**
     * Calculate additional statistics for rider profile
     * 
     * @param int $riderId
     * @return array
     */
    private function calculateRiderStats($riderId)
    {
        // Get total ratings count
        $totalRatings = DB::table('ratings')
            ->where('rateable_id', function($query) use ($riderId) {
                $query->select('user_id')
                      ->from('riders')
                      ->where('id', $riderId);
            })
            ->where('rateable_type', 'App\\Models\\User')
            ->count();
        
        // Get rating distribution (1-5 stars)
        $ratingDistribution = DB::table('ratings')
            ->select('rating_value', DB::raw('COUNT(*) as count'))
            ->where('rateable_id', function($query) use ($riderId) {
                $query->select('user_id')
                      ->from('riders')
                      ->where('id', $riderId);
            })
            ->where('rateable_type', 'App\\Models\\User')
            ->groupBy('rating_value')
            ->pluck('count', 'rating_value')
            ->toArray();
        
        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratingDistribution[$i])) {
                $ratingDistribution[$i] = 0;
            }
        }
        
        // Get completed deliveries this month
        $monthlyDeliveries = DB::table('orders')
            ->where('rider_user_id', function($query) use ($riderId) {
                $query->select('user_id')
                      ->from('riders')
                      ->where('id', $riderId);
            })
            ->where('status', 'delivered')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        return [
            'total_ratings' => $totalRatings,
            'rating_distribution' => $ratingDistribution,
            'monthly_deliveries' => $monthlyDeliveries
        ];
    }

    /**
     * AJAX endpoint to refresh rider rankings
     * Used for real-time updates without page refresh
     * Returns top 10 riders based on merit score
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshRankings()
    {
        try {
            // Get updated riders with merit scores (top 10)
            $riders = $this->getRidersWithMeritScore();
            
            // Identify top 3 riders as suggested
            $suggestedRiders = $riders->take(3)->pluck('id')->toArray();
            
            // Format data for JSON response
            $formattedRiders = $riders->map(function ($rider) use ($suggestedRiders) {
                return [
                    'id' => $rider->id,
                    'name' => $rider->first_name . ' ' . $rider->last_name,
                    'profile_image' => $rider->profile_image_url,
                    'average_rating' => number_format($rider->average_rating, 2),
                    'total_deliveries' => $rider->total_deliveries,
                    'comment_count' => $rider->comment_count,
                    'merit_score' => number_format($rider->merit_score, 2),
                    'is_available' => $rider->is_available,
                    'vehicle_type' => $rider->vehicle_type,
                    'is_suggested' => in_array($rider->id, $suggestedRiders)
                ];
            });
            
            return response()->json([
                'success' => true,
                'riders' => $formattedRiders
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh rankings. Please try again.'
            ], 500);
        }
    }
}
