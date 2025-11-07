<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\Product;

class ProductsController extends Controller
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

      public function create()
    {
        return Inertia::render('Admin/Products/Create');
    }

    public function edit(Product $product)
    {
        // dd($product);
        // // Start with the base query
        // $query = Product::with('category'); // Eager load category relationship

        // // Apply search filter if present
        // $query->when($request->input('search'), function ($query, $search) {
        //     $query->where('name', 'like', "%{$search}%")
        //           ->orWhere('sku', 'like', "%{$search}%");
        // });

        // // Get paginated results and append query string (for filters)
        // $products = $query->paginate(10)->withQueryString();

        // Render the Inertia page component and pass props
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            // 'filters' => $request->only(['search']), // Send filters back to the view
        ]);
    }
}
