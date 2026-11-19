<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Restock;
use App\Models\StockAdjustment;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Hitung kebutuhan stok bahan baku dan kemasan untuk sebuah pesanan,
     * termasuk menangani add-on dan substitusi bahan (misal Susu Oat menggantikan Susu Sapi).
     *
     * @return array [stock_item_id => required_qty]
     */
    public function calculateRequiredStock(Order $order): array
    {
        $requirements = [];

        // Ambil semua item induk (bukan add-on)
        $parentItems = $order->items()
            ->whereNull('parent_id')
            ->whereDoesntHave('product.category', fn($c) => $c->where('slug', 'tambahan'))
            ->with(['product.recipes', 'addOns.product.recipes'])
            ->get();

        foreach ($parentItems as $item) {
            $product = $item->product;
            $variant = $item->variant ?: 'all';
            $qty = $item->qty;

            if ($product->isRacikan()) {
                // Kumpulkan kebutuhan resep dasar minuman
                $itemRequirements = [];
                $baseRecipes = $product->recipes()
                    ->whereIn('variant', [$variant, 'all'])
                    ->get();

                foreach ($baseRecipes as $recipe) {
                    $itemRequirements[$recipe->stock_item_id] = ($itemRequirements[$recipe->stock_item_id] ?? 0)
                        + ($recipe->qty_per_portion * $qty);
                }

                // Cek apakah ada add-on untuk item ini
                foreach ($item->addOns as $addOnItem) {
                    $addOnProduct = $addOnItem->product;
                    $addOnRecipes = $addOnProduct->recipes()->get();

                    foreach ($addOnRecipes as $addOnRecipe) {
                        // Jika resep add-on bersifat substitusi (contoh: Oatmilk menggantikan Susu Sapi Segar)
                        if ($addOnRecipe->replaces_stock_item_id) {
                            $replacedId = $addOnRecipe->replaces_stock_item_id;
                            if (isset($itemRequirements[$replacedId])) {
                                // Batalkan pemotongan bahan lama
                                unset($itemRequirements[$replacedId]);
                            }
                        }

                        // Tambahkan bahan add-on ke kebutuhan
                        $itemRequirements[$addOnRecipe->stock_item_id] = ($itemRequirements[$addOnRecipe->stock_item_id] ?? 0)
                            + ($addOnRecipe->qty_per_portion * $addOnItem->qty);
                    }
                }

                // Gabungkan ke akumulator order keseluruhan
                foreach ($itemRequirements as $stockItemId => $needQty) {
                    $requirements[$stockItemId] = ($requirements[$stockItemId] ?? 0) + $needQty;
                }
            } else {
                // Produk jadi (1:1 unit stok)
                if ($product->stock_item_id) {
                    $requirements[$product->stock_item_id] = ($requirements[$product->stock_item_id] ?? 0) + $qty;
                }
            }
        }

        // Tangani juga jika ada add-on yang diinput sebagai baris item independen (parent_id is null tapi kategorinya Tambahan)
        $independentAddons = $order->items()->whereNull('parent_id')
            ->whereHas('product', fn($q) => $q->where('type', 'racikan')->whereHas('category', fn($c) => $c->where('slug', 'tambahan')))
            ->with('product.recipes')
            ->get();

        foreach ($independentAddons as $addOnItem) {
            foreach ($addOnItem->product->recipes as $addOnRecipe) {
                $requirements[$addOnRecipe->stock_item_id] = ($requirements[$addOnRecipe->stock_item_id] ?? 0)
                    + ($addOnRecipe->qty_per_portion * $addOnItem->qty);
            }
        }

        return $requirements;
    }

    /**
     * Periksa apakah seluruh stok mencukupi untuk memenuhi pesanan.
     *
     * @return array [bool $isAvailable, array $missingItems]
     */
    public function checkAvailability(Order $order): array
    {
        $requirements = $this->calculateRequiredStock($order);
        $missing = [];

        if (empty($requirements)) {
            return [true, []];
        }

        $stockItems = StockItem::whereIn('id', array_keys($requirements))->get()->keyBy('id');

        foreach ($requirements as $stockItemId => $needQty) {
            $stockItem = $stockItems->get($stockItemId);
            if (!$stockItem || $stockItem->current_stock < $needQty) {
                $missing[] = [
                    'id' => $stockItemId,
                    'name' => $stockItem ? $stockItem->name : 'Item tidak dikenal',
                    'available' => $stockItem ? $stockItem->current_stock : 0,
                    'required' => $needQty,
                    'unit' => $stockItem ? $stockItem->unit : '',
                ];
            }
        }

        return [empty($missing), $missing];
    }

    /**
     * Potong stok untuk pesanan terkonfirmasi bayar (BR-01, BR-02, BR-06).
     * Wajib dijalankan dalam DB::transaction dengan row-locking lockForUpdate.
     */
    public function deductForOrder(Order $order, ?User $processedBy = null): void
    {
        DB::transaction(function () use ($order, $processedBy) {
            $requirements = $this->calculateRequiredStock($order);
            if (empty($requirements)) {
                return;
            }

            // Kunci baris stok untuk mencegah race condition (BR-06, Kebutuhan Non-Fungsional 8)
            $stockItems = StockItem::whereIn('id', array_keys($requirements))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Validasi ulang ketersediaan stok setelah dikunci
            foreach ($requirements as $stockItemId => $needQty) {
                $item = $stockItems->get($stockItemId);
                if (!$item || $item->current_stock < $needQty) {
                    $name = $item ? $item->name : "ID: $stockItemId";
                    throw new Exception("Stok tidak mencukupi untuk '$name'. Diperlukan: $needQty, Tersedia: " . ($item ? $item->current_stock : 0));
                }
            }

            // Potong stok dan catat jurnal mutasi OUT
            $now = now();
            foreach ($requirements as $stockItemId => $needQty) {
                $item = $stockItems->get($stockItemId);
                $newBalance = $item->current_stock - $needQty;

                $item->update(['current_stock' => $newBalance]);

                StockMovement::create([
                    'stock_item_id' => $stockItemId,
                    'direction' => 'OUT',
                    'source_type' => 'order',
                    'source_id' => $order->id,
                    'qty' => $needQty,
                    'balance_after' => $newBalance,
                    'occurred_at' => $now,
                    'created_by' => $processedBy ? $processedBy->id : $order->processed_by,
                ]);
            }
        });
    }

    /**
     * Tambah stok dari pembelian/restock (BR-05, BR-11).
     */
    public function addFromRestock(Restock $restock): void
    {
        DB::transaction(function () use ($restock) {
            $restock->load('items');
            $now = now();

            foreach ($restock->items as $item) {
                $stockItem = StockItem::where('id', $item->stock_item_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $newBalance = $stockItem->current_stock + $item->qty_in_base_unit;

                $stockItem->update([
                    'current_stock' => $newBalance,
                    'last_purchase_cost' => $item->unit_cost,
                ]);

                StockMovement::create([
                    'stock_item_id' => $stockItem->id,
                    'direction' => 'IN',
                    'source_type' => 'restock',
                    'source_id' => $restock->id,
                    'qty' => $item->qty_in_base_unit,
                    'balance_after' => $newBalance,
                    'occurred_at' => $now,
                    'created_by' => $restock->created_by,
                ]);
            }
        });
    }

    /**
     * Penyesuaian stok fisik dari hasil Stock Opname (FR-D12).
     */
    public function adjustFromOpname(StockAdjustment $adjustment): void
    {
        DB::transaction(function () use ($adjustment) {
            $stockItem = StockItem::where('id', $adjustment->stock_item_id)
                ->lockForUpdate()
                ->firstOrFail();

            $diff = $adjustment->physical_qty - $stockItem->current_stock;
            $direction = $diff >= 0 ? 'IN' : 'OUT';

            $stockItem->update([
                'current_stock' => $adjustment->physical_qty,
            ]);

            StockMovement::create([
                'stock_item_id' => $stockItem->id,
                'direction' => $direction,
                'source_type' => 'adjustment',
                'source_id' => $adjustment->id,
                'qty' => abs($diff),
                'balance_after' => $adjustment->physical_qty,
                'occurred_at' => now(),
                'created_by' => $adjustment->created_by,
            ]);
        });
    }

    /**
     * Kembalikan stok untuk pesanan yang dibatalkan setelah diproses (BR-07).
     */
    public function returnStockForVoidOrder(Order $order, User $adminUser): void
    {
        DB::transaction(function () use ($order, $adminUser) {
            // Ambil mutasi OUT yang pernah dibuat untuk order ini
            $movements = StockMovement::where('source_type', 'order')
                ->where('source_id', $order->id)
                ->where('direction', 'OUT')
                ->get();

            $now = now();
            foreach ($movements as $m) {
                $stockItem = StockItem::where('id', $m->stock_item_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $newBalance = $stockItem->current_stock + $m->qty;
                $stockItem->update(['current_stock' => $newBalance]);

                StockMovement::create([
                    'stock_item_id' => $stockItem->id,
                    'direction' => 'IN',
                    'source_type' => 'void',
                    'source_id' => $order->id,
                    'qty' => $m->qty,
                    'balance_after' => $newBalance,
                    'occurred_at' => $now,
                    'created_by' => $adminUser->id,
                ]);
            }
        });
    }
}
