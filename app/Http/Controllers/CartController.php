<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $items = $this->cartService->all();
        $subtotal = $this->cartService->subtotal();
        $total = $this->cartService->total();

        return view('customer.cart', compact('items', 'subtotal', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant' => 'nullable|string|in:hot,ice,all',
            'sweetness' => 'nullable|string|in:normal,less,subtle',
            'ice_level' => 'nullable|string|in:normal_ice,less_ice',
            'add_ons' => 'nullable|array',
            'add_ons.*' => 'integer|exists:products,id',
            'qty' => 'required|integer|min:1|max:20',
            'note' => 'nullable|string|max:100',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $variant = $validated['variant'] ?? ($product->has_ice ? 'ice' : 'hot');

        $options = [
            'sweetness' => $validated['sweetness'] ?? 'normal',
            'ice_level' => $validated['ice_level'] ?? 'normal_ice',
        ];

        $addonIds = $validated['add_ons'] ?? [];
        $qty = (int) $validated['qty'];
        $note = $validated['note'] ?? null;

        $this->cartService->add($product, $variant, $options, $addonIds, $qty, $note);

        return redirect()->route('cart.index')->with('success', "'{$product->name}' berhasil ditambahkan ke keranjang.");
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'qty' => 'required|integer|min:0|max:50',
        ]);

        $this->cartService->update($validated['key'], (int) $validated['qty']);

        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
        ]);

        $this->cartService->remove($validated['key']);

        return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang.');
    }
}
