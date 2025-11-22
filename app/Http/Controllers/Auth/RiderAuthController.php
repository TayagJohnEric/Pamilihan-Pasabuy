<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RiderApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\VendorApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class RiderAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.rider.login');
    }
    
   public function create()
    {
        return view('auth.rider.application');
    }

    public function store(Request $request)
{
    // Debug: Log all request data
    \Log::info('Form submission received', [
        'has_files' => $request->hasFile(['nbi_clearance_url', 'valid_id_url', 'selfie_with_id_url']),
        'files' => [
            'nbi_clearance_url' => $request->hasFile('nbi_clearance_url'),
            'valid_id_url' => $request->hasFile('valid_id_url'),
            'selfie_with_id_url' => $request->hasFile('selfie_with_id_url'),
        ]
    ]);

    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'contact_number' => 'required|string|max:20',
        'birth_date' => 'required|date|before:today',
        'address' => 'required|string|max:500',
        'vehicle_type' => 'required|string|max:100',
        'vehicle_model' => 'required|string|max:100',
        'license_plate_number' => 'required|string|max:50',
        'driver_license_number' => 'required|string|max:100',
        'license_expiry_date' => 'required|date|after:today',
        'nbi_clearance_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'valid_id_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'selfie_with_id_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'tin_number' => 'nullable|string|max:100',
    ]);

    try {
        // Handle file uploads
        if ($request->hasFile('nbi_clearance_url') && $request->file('nbi_clearance_url')->isValid()) {
            $file = $request->file('nbi_clearance_url');
            $filename = time() . '_nbi_' . $file->getClientOriginalName();
            $path = $file->storeAs('rider_documents', $filename, 'public');
            $validated['nbi_clearance_url'] = $path;
            \Log::info('NBI file uploaded', ['path' => $path]);
        }

        if ($request->hasFile('valid_id_url') && $request->file('valid_id_url')->isValid()) {
            $file = $request->file('valid_id_url');
            $filename = time() . '_id_' . $file->getClientOriginalName();
            $path = $file->storeAs('rider_documents', $filename, 'public');
            $validated['valid_id_url'] = $path;
            \Log::info('Valid ID file uploaded', ['path' => $path]);
        }

        if ($request->hasFile('selfie_with_id_url') && $request->file('selfie_with_id_url')->isValid()) {
            $file = $request->file('selfie_with_id_url');
            $filename = time() . '_selfie_' . $file->getClientOriginalName();
            $path = $file->storeAs('rider_documents', $filename, 'public');
            $validated['selfie_with_id_url'] = $path;
            \Log::info('Selfie file uploaded', ['path' => $path]);
        }

        // Create the record
        $application = RiderApplication::create($validated);
        \Log::info('Application created', ['id' => $application->id]);

        return redirect()->back()->with('success', 'Application submitted successfully! We will review your application and contact you soon.');

    } catch (\Exception $e) {
        \Log::error('Application submission failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withErrors(['error' => 'Failed to submit application: ' . $e->getMessage()])
            ->withInput();
    }
}

   // Updated login function to set is_available to true
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $credentials['role'] = 'rider';

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Set is_available to true for the rider
        $user = Auth::user();
        $needsSelfieVerification = true;

        if ($user && $user->rider) {
            $user->rider->is_available = true;
            $user->rider->save();
        }

        $request->session()->put('requires_selfie_verification', true);

        $redirectUrl = route('rider.selfie-verification.show');

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Login successful',
                'redirect' => $redirectUrl,
            ], 200);
        }

        return redirect($redirectUrl);
    }

    if ($request->ajax()) {
        return response()->json([
            'errors' => ['email' => ['Invalid credentials or not a rider.']]
        ], 422);
    }

    return back()->withErrors([
        'email' => 'Invalid credentials or not a rider.',
    ]);
}

// Updated logout function to set is_available to false
public function logout(Request $request)
{
    // Set is_available to false before logging out
    // uncomment this when in production for more authenticity
    
   /* $user = Auth::user();
    if ($user && $user->rider) {
        $user->rider->is_available = false;
        $user->rider->save();
    } */

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('rider.login');
}

public function showSelfieVerificationForm()
{
    $user = Auth::user();

    if (!$user || $user->role !== 'rider') {
        return redirect()->route('rider.login');
    }

    $rider = $user->rider;

    if (!$rider) {
        Auth::logout();
        return redirect()->route('rider.login')
            ->withErrors(['email' => 'Unable to find rider profile. Please contact support.']);
    }

    return view('auth.rider.selfie-verification', [
        'rider' => $rider,
    ]);
}

public function uploadSelfieVerification(Request $request)
{
    $user = Auth::user();

    if (!$user || $user->role !== 'rider' || !$user->rider) {
        return redirect()->route('rider.login');
    }

    $request->validate([
        'selfie' => 'required|image|mimes:jpg,jpeg,png|max:4096',
    ]);

    $rider = $user->rider;

    try {
        $file = $request->file('selfie');
        $path = $file->store('rider_selfies', 'public');

        if ($rider->selfie_verification_url && Storage::disk('public')->exists($rider->selfie_verification_url)) {
            Storage::disk('public')->delete($rider->selfie_verification_url);
        }

        $rider->selfie_verification_url = $path;
        $rider->save();

        $request->session()->forget('requires_selfie_verification');

        return redirect()->route('rider.dashboard')
            ->with('success', 'Selfie verification uploaded successfully.');
    } catch (\Exception $e) {
        \Log::error('Rider selfie upload failed', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);

        return back()->withErrors([
            'selfie' => 'Failed to upload selfie. Please try again.',
        ])->withInput();
    }
}
}
