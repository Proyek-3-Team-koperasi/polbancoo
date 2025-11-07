<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Start with the base query
        $query = Product::with('category'); // Eager load category relationship

        // Apply search filter if present
        $query->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        });

        // Get paginated results and append query string (for filters)
        $products = $query->paginate(10)->withQueryString();

        // Render the Inertia page component and pass props
        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            // 'filters' => $request->only(['search']), // Send filters back to the view
        ]);
    }

  
}
