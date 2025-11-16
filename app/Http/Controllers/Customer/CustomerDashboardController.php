<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;


class CustomerDashboardController extends Controller
{
    public function dashboard(Request $request)
{
    $query = Product::with(['vendor.user', 'category'])
        ->where('is_available', true)
        ->whereHas('vendor', function (Builder $query) {
            $query->where('is_active', true)
                  ->where('is_accepting_orders', true);
        });

    // Apply search filter
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function (Builder $q) use ($searchTerm) {
            $q->where('product_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('vendor', function (Builder $vendorQuery) use ($searchTerm) {
                  $vendorQuery->where('vendor_name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    // Apply budget-based filter if requested
    if ($request->boolean('budget_based')) {
        $query->where('is_budget_based', true);
    }

    // Apply sorting
    $sortBy = $request->get('sort', 'latest');
    switch ($sortBy) {
        case 'price_low':
            $query->orderBy('price', 'asc');
            break;
        case 'price_high':
            $query->orderBy('price', 'desc');
            break;
        case 'name':
            $query->orderBy('product_name', 'asc');
            break;
        default:
            $query->latest();
            break;
    }

    $products = $query->paginate(12)->withQueryString();
    $categories = Category::orderBy('category_name')->get();
    //  Get 5 random vendors
    $vendors = Vendor::where('is_active', true)
        ->where('is_accepting_orders', true)
        ->inRandomOrder()
        ->take(5)
        ->get();

    // Get featured products for homepage (randomized)
    $featuredProducts = Product::with(['vendor.user', 'category'])
        ->where('is_available', true)
        ->whereHas('vendor', function (Builder $query) {
            $query->where('is_active', true)
                  ->where('is_accepting_orders', true);
        })
        ->inRandomOrder()
        ->take(8)
        ->get();

    // Get budget-based products for homepage (randomized)
    $budgetProducts = Product::with(['vendor.user', 'category'])
        ->where('is_available', true)
        ->where('is_budget_based', true)
        ->whereHas('vendor', function (Builder $query) {
            $query->where('is_active', true)
                  ->where('is_accepting_orders', true);
        })
        ->inRandomOrder()
        ->take(8)
        ->get();

    // Get products grouped by category for homepage
    $categoryProducts = Category::with(['products' => function ($query) {
            $query->where('is_available', true)
                  ->whereHas('vendor', function (Builder $vendorQuery) {
                      $vendorQuery->where('is_active', true)
                                ->where('is_accepting_orders', true);
                  })
                  ->with(['vendor.user', 'category'])
                  ->latest()
                  ->take(4); // Limit to 4 products per category
        }])
        ->whereHas('products', function ($query) {
            $query->where('is_available', true)
                  ->whereHas('vendor', function (Builder $vendorQuery) {
                      $vendorQuery->where('is_active', true)
                                ->where('is_accepting_orders', true);
                  });
        })
        ->orderBy('category_name')
        ->take(6) // Limit to 6 categories
        ->get();

    return view('customer.home.home', compact('products', 'categories', 'featuredProducts', 'vendors', 'budgetProducts', 'categoryProducts'));
}



    
}
