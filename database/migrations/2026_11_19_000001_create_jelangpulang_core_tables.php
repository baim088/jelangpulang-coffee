<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pengaturan Toko
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 2. Kategori Menu
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Item Stok (Bahan Baku, Kemasan, Produk Jadi)
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'bahan', 'kemasan', 'jadi'
            $table->string('unit'); // 'gram', 'ml', 'pcs'
            $table->decimal('current_stock', 12, 2)->default(0);
            $table->decimal('min_stock', 12, 2)->default(0);
            $table->integer('last_purchase_cost')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Produk / Menu
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->integer('price');
            $table->string('type')->default('racikan'); // 'racikan', 'jadi'
            $table->boolean('has_hot')->default(true);
            $table->boolean('has_ice')->default(true);
            $table->integer('ice_price_delta')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Resep (Bill of Materials)
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->string('variant')->default('all'); // 'hot', 'ice', 'all'
            $table->decimal('qty_per_portion', 10, 2);
            $table->foreignId('replaces_stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $table->timestamps();
        });

        // 6. Mapping Add-On ke Produk Relevan
        Schema::create('product_add_ons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('add_on_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['product_id', 'add_on_id']);
        });

        // 7. Restock Header & Items
        Schema::create('restocks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->timestamp('restock_date');
            $table->text('supplier_note')->nullable();
            $table->integer('total_cost')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('restock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restock_id')->constrained('restocks')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->string('purchase_unit'); // kg, slop, pack, dll.
            $table->decimal('purchase_qty', 10, 2);
            $table->decimal('conversion_factor', 10, 2); // misal 1 kg = 1000
            $table->decimal('qty_in_base_unit', 10, 2);
            $table->integer('unit_cost');
            $table->integer('subtotal_cost');
            $table->timestamps();
        });

        // 8. Stock Opname / Penyesuaian
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->decimal('system_qty', 12, 2);
            $table->decimal('physical_qty', 12, 2);
            $table->decimal('diff_qty', 12, 2);
            $table->text('reason');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 9. Jurnal Mutasi Stok (Append-Only)
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->string('direction'); // 'IN', 'OUT'
            $table->string('source_type'); // 'restock', 'order', 'adjustment', 'void'
            $table->unsignedBigInteger('source_id')->nullable();
            $table->decimal('qty', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->timestamp('occurred_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['stock_item_id', 'occurred_at']);
        });

        // 10. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique(); // JP-YYYYMMDD-NNNN
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('table_no')->nullable();
            $table->string('channel')->default('self_order'); // 'self_order', 'walk_in'
            $table->string('status')->default('menunggu_verifikasi'); // 'menunggu_verifikasi', 'diproses', 'selesai', 'dibatalkan'
            $table->string('payment_method'); // 'qris', 'cash', 'pay_at_counter'
            $table->integer('subtotal');
            $table->integer('total');
            $table->integer('cash_received')->nullable();
            $table->integer('change_amount')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        // 11. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('order_items')->cascadeOnDelete();
            $table->string('product_name');
            $table->string('variant')->nullable(); // 'hot', 'ice'
            $table->json('options')->nullable(); // sweetness, ice_level, etc.
            $table->integer('qty');
            $table->integer('unit_price');
            $table->integer('subtotal');
            $table->string('note')->nullable();
            $table->timestamps();
        });

        // 12. Refunds
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->integer('amount');
            $table->text('reason');
            $table->string('method')->default('tunai'); // 'tunai', 'transfer'
            $table->string('status')->default('diajukan'); // 'diajukan', 'disetujui', 'selesai', 'ditolak'
            $table->string('stok_action')->default('none'); // 'void', 'waste', 'none'
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 13. Audit Log
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('restock_items');
        Schema::dropIfExists('restocks');
        Schema::dropIfExists('product_add_ons');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('store_settings');
    }
};
