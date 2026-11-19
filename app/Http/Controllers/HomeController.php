<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Menu unggulan dari database
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['category', 'addOns'])
            ->take(6)
            ->get();

        $categories = Category::where('is_active', true)
            ->where('slug', '!=', 'tambahan')
            ->orderBy('sort_order')
            ->get();

        $storeHours = StoreSetting::get('store_hours', '15:00 - 22:00 WIB');
        $storeAddress = StoreSetting::get('store_address', 'Jl. Sore Hari No. 12, Senja Raya');
        $storeTagline = StoreSetting::get('store_tagline', 'Satu gelas sebelum arah pulang');

        // Cek status buka/tutup berdasarkan jam operasional kedai (15:00 - 22:00)
        $currentHour = (int) now()->timezone('Asia/Jakarta')->format('H');
        $isOpen = ($currentHour >= 15 && $currentHour < 22);

        return view('customer.home', compact(
            'featuredProducts',
            'categories',
            'storeHours',
            'storeAddress',
            'storeTagline',
            'isOpen'
        ));
    }
}
