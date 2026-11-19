<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $selectedCat = $request->query('kategori', 'all');

        $categories = Category::where('is_active', true)
            ->where('slug', '!=', 'tambahan')
            ->orderBy('sort_order')
            ->get();

        $query = Product::where('is_active', true)
            ->whereHas('category', fn($q) => $q->where('slug', '!=', 'tambahan'))
            ->with(['category', 'addOns', 'recipes.stockItem']);

        if ($selectedCat !== 'all') {
            $query->whereHas('category', fn($q) => $q->where('slug', $selectedCat));
        }

        if ($search = $request->query('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->orderBy('is_featured', 'desc')->get();

        return view('customer.catalog', compact('categories', 'products', 'selectedCat'));
    }
}
