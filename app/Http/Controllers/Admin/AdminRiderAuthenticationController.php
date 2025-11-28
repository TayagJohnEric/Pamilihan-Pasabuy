<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminRiderAuthenticationController extends Controller
{
    public function index(Request $request)
    {
        $query = Rider::with(['user' => function ($relation) {
            $relation->select('id', 'first_name', 'last_name', 'email', 'phone_number', 'last_login_at', 'is_active');
        }]);

        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('verification_status') && $request->verification_status !== 'all') {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('availability') && $request->availability !== 'all') {
            $query->where('is_available', $request->availability === 'available');
        }

        if ($request->filled('selfie_status') && $request->selfie_status !== 'all') {
            $request->selfie_status === 'with'
                ? $query->whereNotNull('selfie_verification_url')
                : $query->whereNull('selfie_verification_url');
        }

        $riders = $query
            ->orderByDesc('updated_at')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Rider::count(),
            'withSelfie' => Rider::whereNotNull('selfie_verification_url')->count(),
            'withoutSelfie' => Rider::whereNull('selfie_verification_url')->count(),
            'recentLogins' => Rider::whereHas('user', function ($userQuery) {
                $userQuery->where('last_login_at', '>=', Carbon::now()->subDay());
            })->count(),
        ];

        $verificationStatuses = Rider::select('verification_status')
            ->distinct()
            ->pluck('verification_status')
            ->filter()
            ->values();

        return view('admin.platform-operation.rider-authentication.index', [
            'riders' => $riders,
            'stats' => $stats,
            'verificationStatuses' => $verificationStatuses,
            'filters' => [
                'search' => $request->search,
                'verification_status' => $request->verification_status ?? 'all',
                'availability' => $request->availability ?? 'all',
                'selfie_status' => $request->selfie_status ?? 'all',
            ],
        ]);
    }
}
