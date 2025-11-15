<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/** I MIGHT: rename this controller */
class CustomerProductController extends Controller
{
    
/**
     * Display the homepage with featured products
     */
    public function index(Request $request)
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

        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'random');
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
            case 'latest':
                $query->latest();
                break;
            default:
                $query->inRandomOrder();
                break;
        }

$products = $query->get();
        $categories = Category::orderBy('category_name')->get();
        //  Get 5 random vendors
        $vendors = Vendor::inRandomOrder()->take(5)->get();
        
        // Get featured products for homepage
        $featuredProducts = Product::with(['vendor.user', 'category'])
            ->where('is_available', true)
            ->whereHas('vendor', function (Builder $query) {
                $query->where('is_active', true)
                      ->where('is_accepting_orders', true);
            })
            ->latest()
            ->take(8)
            ->get();

        return view('customer.shop.index', compact('products', 'categories', 'featuredProducts', 'vendors'));
    }

    /**
     * Display products by category
     */
    public function category(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $query = Product::with(['vendor.user', 'category'])
            ->where('category_id', $categoryId)
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
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
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

        return view('customer.shop.category', compact('products', 'category', 'categories'));
    }

    /**
     * Display a single product detail
     */
    public function show($id)
    {
        $product = Product::with([
            'vendor.user', 
            'category',
            'vendor.ratingsReceived' => function ($query) {
                $query->with('user')->latest()->take(5);
            }
        ])
        ->where('is_available', true)
        ->whereHas('vendor', function (Builder $query) {
            $query->where('is_active', true);
        })
        ->findOrFail($id);

        // Get related products from the same vendor
        $relatedProducts = Product::with(['vendor.user', 'category'])
            ->where('vendor_id', $product->vendor_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        // Get similar products from the same category
        $similarProducts = Product::with(['vendor.user', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->whereHas('vendor', function (Builder $query) {
                $query->where('is_active', true)
                      ->where('is_accepting_orders', true);
            })
            ->take(4)
            ->get();

        return view('customer.shop.show', compact('product', 'relatedProducts', 'similarProducts'));
    }

    /**
     * Display vendor shop page
     */
    public function vendor(Request $request, $vendorId)
    {
        $vendor = Vendor::with('user')
            ->where('is_active', true)
            ->findOrFail($vendorId);

        $query = Product::with(['category'])
            ->where('vendor_id', $vendorId)
            ->where('is_available', true);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->where('product_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
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
        
        // Get categories for this vendor's products
        $categories = Category::whereHas('products', function (Builder $query) use ($vendorId) {
            $query->where('vendor_id', $vendorId)
                  ->where('is_available', true);
        })->orderBy('category_name')->get();

        return view('customer.shop.vendor', compact('vendor', 'products', 'categories'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('q', '');
        
        if (empty($searchTerm)) {
            return redirect()->route('products.index');
        }

        $query = Product::with(['vendor.user', 'category'])
            ->where('is_available', true)
            ->whereHas('vendor', function (Builder $query) {
                $query->where('is_active', true)
                      ->where('is_accepting_orders', true);
            })
            ->where(function (Builder $q) use ($searchTerm) {
                $q->where('product_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('vendor', function (Builder $vendorQuery) use ($searchTerm) {
                      $vendorQuery->where('vendor_name', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('category', function (Builder $categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('category_name', 'LIKE', "%{$searchTerm}%");
                  });
            });

        // Apply sorting
        $sortBy = $request->get('sort', 'relevance');
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
            case 'random':
                $query->inRandomOrder();
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('category_name')->get();

        return view('customer.shop.search', compact('products', 'searchTerm', 'categories'));
    }

}
