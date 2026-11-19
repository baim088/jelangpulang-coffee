<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    private const SESSION_KEY = 'jp_cart';

    public function all(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        $cart = $this->all();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['qty'];
        }
        return $total;
    }

    public function subtotal(): int
    {
        $cart = $this->all();
        $sum = 0;
        foreach ($cart as $item) {
            $sum += $item['subtotal'];
        }
        return $sum;
    }

    public function total(): int
    {
        return $this->subtotal();
    }

    public function add(Product $product, string $variant = 'ice', array $options = [], array $addonIds = [], int $qty = 1, ?string $note = null): void
    {
        $cart = $this->all();

        // Hitung harga dasar produk (ditambah ice delta jika varian ice)
        $unitPrice = $product->price;
        if ($variant === 'ice' && $product->ice_price_delta > 0) {
            $unitPrice += $product->ice_price_delta;
        }

        // Ambil add-on yang dipilih (hanya yang valid dan terelasi)
        $addons = [];
        $addonsPrice = 0;
        if (!empty($addonIds)) {
            $validAddons = $product->addOns()->whereIn('products.id', $addonIds)->get();
            foreach ($validAddons as $addon) {
                $addons[] = [
                    'product_id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price,
                    'qty' => 1,
                ];
                $addonsPrice += $addon->price;
            }
        }

        // Item key unik berdasarkan kombinasi opsi
        sort($addonIds);
        $key = md5(implode('|', [
            $product->id,
            $variant,
            $options['sweetness'] ?? 'normal',
            $options['ice_level'] ?? 'normal',
            implode(',', $addonIds),
            trim($note ?? ''),
        ]));

        $singleItemTotal = $unitPrice + $addonsPrice;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
            $cart[$key]['subtotal'] = $cart[$key]['qty'] * $singleItemTotal;
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'photo' => $product->photo,
                'variant' => $variant,
                'options' => $options,
                'unit_price' => $unitPrice,
                'addons_price' => $addonsPrice,
                'single_total' => $singleItemTotal,
                'qty' => $qty,
                'subtotal' => $singleItemTotal * $qty,
                'note' => $note,
                'add_ons' => $addons,
            ];
        }

        session()->put(self::SESSION_KEY, $cart);
    }

    public function update(string $key, int $qty): void
    {
        $cart = $this->all();
        if (isset($cart[$key])) {
            if ($qty <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['qty'] = $qty;
                $cart[$key]['subtotal'] = $cart[$key]['single_total'] * $qty;
            }
            session()->put(self::SESSION_KEY, $cart);
        }
    }

    public function remove(string $key): void
    {
        $cart = $this->all();
        unset($cart[$key]);
        session()->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}
