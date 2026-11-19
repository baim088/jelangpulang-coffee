<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class JelangPulangSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna (3 Aktor)
        $admin = User::firstOrCreate(['email' => 'admin@jelangpulang.test'], [
            'name' => 'Pak Anton',
            'phone' => '081200000001',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $kasir = User::firstOrCreate(['email' => 'kasir@jelangpulang.test'], [
            'name' => 'Sari (Kasir)',
            'phone' => '081200000002',
            'role' => 'kasir',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $user = User::firstOrCreate(['email' => 'user@jelangpulang.test'], [
            'name' => 'Dimas',
            'phone' => '081200000003',
            'role' => 'user',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        // 2. Pengaturan Toko
        $settings = [
            'store_name' => 'JelangPulang',
            'store_tagline' => 'Satu gelas sebelum arah pulang',
            'store_description' => 'Kedai kopi artisanal sore hari, jeda transisi antara hiruk-pikuk aktivitas dan kenyamanan rumah.',
            'store_address' => 'Jl. Sore Hari No. 12, Senja Raya',
            'store_phone' => '0812-3456-7890',
            'store_hours' => '15:00 - 22:00 WIB',
            'payment_timeout_minutes' => '30',
            'qris_image' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=00020101021226670016ID.CO.SHOPEE.WWW0118936009180000000000021500000000000000005204581253033605802ID5912JELANGPULANG6007JAKARTA61051234062070703A016304ABCD',
            'is_open' => '1',
        ];
        foreach ($settings as $k => $v) {
            StoreSetting::set($k, $v);
        }

        // 3. Item Stok (Bahan, Kemasan, Jadi)
        $stockData = [
            // Bahan Baku
            ['name' => 'Biji Kopi House Blend', 'type' => 'bahan', 'unit' => 'gram', 'current_stock' => 5000, 'min_stock' => 1000, 'cost' => 180], // Rp 180.000 / kg
            ['name' => 'Susu Sapi Segar', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 20000, 'min_stock' => 5000, 'cost' => 20], // Rp 20.000 / L
            ['name' => 'Susu Oat', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 10000, 'min_stock' => 3000, 'cost' => 45], // Rp 45.000 / L
            ['name' => 'Gula Aren Cair', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 10000, 'min_stock' => 2000, 'cost' => 30],
            ['name' => 'Sea Salt Cream', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 5000, 'min_stock' => 1000, 'cost' => 50],
            ['name' => 'Sirup Karamel', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 4000, 'min_stock' => 1000, 'cost' => 80],
            ['name' => 'Ekstrak Pandan & Santan', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 5000, 'min_stock' => 1000, 'cost' => 25],
            ['name' => 'Cokelat Bubuk Pekat', 'type' => 'bahan', 'unit' => 'gram', 'current_stock' => 4000, 'min_stock' => 1000, 'cost' => 100],
            ['name' => 'Matcha Bubuk Jepang', 'type' => 'bahan', 'unit' => 'gram', 'current_stock' => 3000, 'min_stock' => 500, 'cost' => 250],
            ['name' => 'Hojicha Bubuk', 'type' => 'bahan', 'unit' => 'gram', 'current_stock' => 3000, 'min_stock' => 500, 'cost' => 250],
            ['name' => 'Teh Hitam Pekat', 'type' => 'bahan', 'unit' => 'gram', 'current_stock' => 3000, 'min_stock' => 500, 'cost' => 80],
            ['name' => 'Lemon Sereh & Madu', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 5000, 'min_stock' => 1000, 'cost' => 40],
            ['name' => 'Sirup Buah & Topping', 'type' => 'bahan', 'unit' => 'ml', 'current_stock' => 5000, 'min_stock' => 1000, 'cost' => 60],
            ['name' => 'Pisang Kepok & Keju', 'type' => 'bahan', 'unit' => 'pcs', 'current_stock' => 100, 'min_stock' => 20, 'cost' => 5000],
            ['name' => 'Roti Tawar & Selai', 'type' => 'bahan', 'unit' => 'pcs', 'current_stock' => 80, 'min_stock' => 20, 'cost' => 5000],

            // Kemasan
            ['name' => 'Cup Dingin 16oz (Ice)', 'type' => 'kemasan', 'unit' => 'pcs', 'current_stock' => 500, 'min_stock' => 100, 'cost' => 600],
            ['name' => 'Lid Cup Dingin', 'type' => 'kemasan', 'unit' => 'pcs', 'current_stock' => 500, 'min_stock' => 100, 'cost' => 200],
            ['name' => 'Cup Panas 8oz (Hot)', 'type' => 'kemasan', 'unit' => 'pcs', 'current_stock' => 300, 'min_stock' => 50, 'cost' => 800],
            ['name' => 'Lid Cup Panas', 'type' => 'kemasan', 'unit' => 'pcs', 'current_stock' => 300, 'min_stock' => 50, 'cost' => 300],
            ['name' => 'Sedotan Kertas / Plastik', 'type' => 'kemasan', 'unit' => 'pcs', 'current_stock' => 600, 'min_stock' => 100, 'cost' => 150],

            // Produk Jadi (1:1 unit stok)
            ['name' => 'Dimsum Ayam (Porsi)', 'type' => 'jadi', 'unit' => 'pcs', 'current_stock' => 50, 'min_stock' => 10, 'cost' => 9000],
            ['name' => 'Cireng Rujak (Porsi)', 'type' => 'jadi', 'unit' => 'pcs', 'current_stock' => 50, 'min_stock' => 10, 'cost' => 7000],
            ['name' => 'Butter Croissant', 'type' => 'jadi', 'unit' => 'pcs', 'current_stock' => 30, 'min_stock' => 10, 'cost' => 9000],
        ];

        $stockItems = [];
        foreach ($stockData as $item) {
            $si = StockItem::firstOrCreate(['name' => $item['name']], [
                'type' => $item['type'],
                'unit' => $item['unit'],
                'current_stock' => $item['current_stock'],
                'min_stock' => $item['min_stock'],
                'last_purchase_cost' => $item['cost'],
                'is_active' => true,
            ]);
            $stockItems[$item['name']] = $si;

            // Catat saldo awal di StockMovement
            StockMovement::create([
                'stock_item_id' => $si->id,
                'direction' => 'IN',
                'source_type' => 'restock',
                'source_id' => null,
                'qty' => $item['current_stock'],
                'balance_after' => $item['current_stock'],
                'occurred_at' => now(),
                'created_by' => $admin->id,
            ]);
        }

        // 4. Kategori Menu
        $cats = [
            'signature' => ['name' => 'Signature Jelang Pulang', 'sort' => 1],
            'kopi-klasik' => ['name' => 'Kopi Klasik', 'sort' => 2],
            'seduh-manual' => ['name' => 'Seduh Manual', 'sort' => 3],
            'non-kopi' => ['name' => 'Non-Kopi (Aman Malam Hari)', 'sort' => 4],
            'camilan' => ['name' => 'Camilan Pengganjal', 'sort' => 5],
            'tambahan' => ['name' => 'Tambahan / Add-On', 'sort' => 6],
        ];

        $categories = [];
        foreach ($cats as $slug => $data) {
            $categories[$slug] = Category::firstOrCreate(['slug' => $slug], [
                'name' => $data['name'],
                'sort_order' => $data['sort'],
                'is_active' => true,
            ]);
        }

        // Helper kemasan ID
        $cupIce = $stockItems['Cup Dingin 16oz (Ice)']->id;
        $lidIce = $stockItems['Lid Cup Dingin']->id;
        $cupHot = $stockItems['Cup Panas 8oz (Hot)']->id;
        $lidHot = $stockItems['Lid Cup Panas']->id;
        $sedotan = $stockItems['Sedotan Kertas / Plastik']->id;

        // 5. Tambahan / Add-On Produk
        $addOnData = [
            'Extra shot' => [
                'price' => 5000,
                'recipe' => [
                    ['stock_item_id' => $stockItems['Biji Kopi House Blend']->id, 'variant' => 'all', 'qty' => 9],
                ]
            ],
            'Oatmilk' => [
                'price' => 7000,
                'recipe' => [
                    [
                        'stock_item_id' => $stockItems['Susu Oat']->id,
                        'variant' => 'all',
                        'qty' => 120,
                        'replaces' => $stockItems['Susu Sapi Segar']->id // SUBSTITUSI
                    ],
                ]
            ],
            'Extra sea salt cream' => [
                'price' => 4000,
                'recipe' => [
                    ['stock_item_id' => $stockItems['Sea Salt Cream']->id, 'variant' => 'all', 'qty' => 30],
                ]
            ],
            'Gula aren' => [
                'price' => 3000,
                'recipe' => [
                    ['stock_item_id' => $stockItems['Gula Aren Cair']->id, 'variant' => 'all', 'qty' => 20],
                ]
            ],
        ];

        $addOnProducts = [];
        foreach ($addOnData as $name => $info) {
            $p = Product::firstOrCreate(['slug' => Str::slug($name)], [
                'category_id' => $categories['tambahan']->id,
                'name' => $name,
                'description' => 'Tambahan pilihan untuk minuman Anda',
                'price' => $info['price'],
                'type' => 'racikan',
                'has_hot' => false,
                'has_ice' => false,
                'ice_price_delta' => 0,
                'is_featured' => false,
                'is_active' => true,
            ]);
            $addOnProducts[$name] = $p;

            // Recipes
            foreach ($info['recipe'] as $rcp) {
                Recipe::firstOrCreate([
                    'product_id' => $p->id,
                    'stock_item_id' => $rcp['stock_item_id'],
                ], [
                    'variant' => $rcp['variant'],
                    'qty_per_portion' => $rcp['qty'],
                    'replaces_stock_item_id' => $rcp['replaces'] ?? null,
                ]);
            }
        }

        // 6. Menu Utama
        $menuList = [
            // Signature
            [
                'cat' => 'signature', 'name' => 'Kopi Jelang Pulang', 'price' => 22000, 'ice_delta' => 2000, 'featured' => true,
                'desc' => 'Espresso, susu segar, gula aren, krim gurih (sea salt cream).',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot', 'Oatmilk', 'Extra sea salt cream', 'Gula aren'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 120],
                    ['item' => 'Gula Aren Cair', 'variant' => 'all', 'qty' => 20],
                    ['item' => 'Sea Salt Cream', 'variant' => 'all', 'qty' => 20],
                ]
            ],
            [
                'cat' => 'signature', 'name' => 'Singgah Sebentar', 'price' => 23000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Espresso, susu segar, ekstrak pandan, santan kelapa.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot', 'Oatmilk'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 100],
                    ['item' => 'Ekstrak Pandan & Santan', 'variant' => 'all', 'qty' => 30],
                ]
            ],
            [
                'cat' => 'signature', 'name' => 'Lampu Kuning', 'price' => 24000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Cold brew, perasan jeruk sunkist, madu, air soda.',
                'has_hot' => false, 'has_ice' => true,
                'allowed_addons' => [],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'ice', 'qty' => 20],
                    ['item' => 'Lemon Sereh & Madu', 'variant' => 'ice', 'qty' => 30],
                ]
            ],
            [
                'cat' => 'signature', 'name' => 'Sisa Lelah', 'price' => 24000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Espresso, cokelat pekat (dark chocolate), susu, kayu manis.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot', 'Oatmilk'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 100],
                    ['item' => 'Cokelat Bubuk Pekat', 'variant' => 'all', 'qty' => 20],
                ]
            ],
            [
                'cat' => 'signature', 'name' => 'Jalur Lambat', 'price' => 26000, 'ice_delta' => 2000, 'featured' => true,
                'desc' => 'Espresso, susu oat (oat milk), sirup karamel.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot'], // Oatmilk tidak diizinkan karena sudah oat
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                    ['item' => 'Susu Oat', 'variant' => 'all', 'qty' => 120],
                    ['item' => 'Sirup Karamel', 'variant' => 'all', 'qty' => 20],
                ]
            ],

            // Kopi Klasik
            [
                'cat' => 'kopi-klasik', 'name' => 'Americano / Long Black', 'price' => 18000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Espresso ganda dan air mineral segar.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                ]
            ],
            [
                'cat' => 'kopi-klasik', 'name' => 'Caffè Latte', 'price' => 21000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Espresso dengan susu segar dan busa susu tipis lembut.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot', 'Oatmilk'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 140],
                ]
            ],
            [
                'cat' => 'kopi-klasik', 'name' => 'Cappuccino', 'price' => 21000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Espresso dengan susu segar dan busa susu tebal klasik.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot', 'Oatmilk'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 120],
                ]
            ],
            [
                'cat' => 'kopi-klasik', 'name' => 'Mochaccino', 'price' => 23000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Espresso, bubuk cokelat, dan susu segar.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Extra shot', 'Oatmilk'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 18],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 100],
                    ['item' => 'Cokelat Bubuk Pekat', 'variant' => 'all', 'qty' => 15],
                ]
            ],
            [
                'cat' => 'kopi-klasik', 'name' => 'Piccolo / Espresso', 'price' => 18000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Ekstrak kopi murni pekat dengan sedikit susu hangat.',
                'has_hot' => true, 'has_ice' => false,
                'allowed_addons' => ['Extra shot'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'hot', 'qty' => 18],
                ]
            ],

            // Seduh Manual
            [
                'cat' => 'seduh-manual', 'name' => 'V60 / Japanese Iced Coffee', 'price' => 25000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Biji kopi single origin pilihan dengan metode seduh saring manual filter.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => [],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'all', 'qty' => 15],
                ]
            ],
            [
                'cat' => 'seduh-manual', 'name' => 'Tubruk Pilihan', 'price' => 15000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Bubuk kopi giling halus dan air panas tanpa saringan khas Nusantara.',
                'has_hot' => true, 'has_ice' => false,
                'allowed_addons' => ['Gula aren'],
                'recipes' => [
                    ['item' => 'Biji Kopi House Blend', 'variant' => 'hot', 'qty' => 12],
                ]
            ],

            // Non-Kopi
            [
                'cat' => 'non-kopi', 'name' => 'Cokelat Pekat', 'price' => 22000, 'ice_delta' => 2000, 'featured' => true,
                'desc' => 'Dark chocolate murni dan susu segar yang menenangkan.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Oatmilk'],
                'recipes' => [
                    ['item' => 'Cokelat Bubuk Pekat', 'variant' => 'all', 'qty' => 30],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 120],
                ]
            ],
            [
                'cat' => 'non-kopi', 'name' => 'Matcha Latte', 'price' => 23000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Bubuk teh hijau Jepang (matcha) otentik dan susu segar.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Oatmilk'],
                'recipes' => [
                    ['item' => 'Matcha Bubuk Jepang', 'variant' => 'all', 'qty' => 15],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 120],
                ]
            ],
            [
                'cat' => 'non-kopi', 'name' => 'Hojicha Latte', 'price' => 24000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Teh hijau panggang Jepang aromatik dan susu segar.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Oatmilk'],
                'recipes' => [
                    ['item' => 'Hojicha Bubuk', 'variant' => 'all', 'qty' => 15],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 120],
                ]
            ],
            [
                'cat' => 'non-kopi', 'name' => 'Teh Tarik Susu', 'price' => 18000, 'ice_delta' => 2000, 'featured' => false,
                'desc' => 'Teh hitam pekat dengan susu kental manis dan susu evaporasi lembut.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => ['Gula aren'],
                'recipes' => [
                    ['item' => 'Teh Hitam Pekat', 'variant' => 'all', 'qty' => 20],
                    ['item' => 'Susu Sapi Segar', 'variant' => 'all', 'qty' => 100],
                ]
            ],
            [
                'cat' => 'non-kopi', 'name' => 'Lemon Sereh Madu', 'price' => 19000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Perasan lemon, rebusan batang sereh harum, dan madu hutan.',
                'has_hot' => true, 'has_ice' => true,
                'allowed_addons' => [],
                'recipes' => [
                    ['item' => 'Lemon Sereh & Madu', 'variant' => 'all', 'qty' => 40],
                ]
            ],
            [
                'cat' => 'non-kopi', 'name' => 'Lychee Tea / Peach Tea', 'price' => 18000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Teh hitam dingin dengan sirup dan potongan buah leci atau persik.',
                'has_hot' => false, 'has_ice' => true,
                'allowed_addons' => [],
                'recipes' => [
                    ['item' => 'Teh Hitam Pekat', 'variant' => 'ice', 'qty' => 15],
                    ['item' => 'Sirup Buah & Topping', 'variant' => 'ice', 'qty' => 30],
                ]
            ],

            // Camilan Pengganjal
            [
                'cat' => 'camilan', 'name' => 'Pisang Goreng Gula Aren / Keju', 'price' => 16000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Pisang kepok goreng renyah dengan saus gula aren atau keju parut gurih.',
                'has_hot' => false, 'has_ice' => false,
                'allowed_addons' => [],
                'recipes' => [
                    ['item' => 'Pisang Kepok & Keju', 'variant' => 'all', 'qty' => 1],
                ]
            ],
            [
                'cat' => 'camilan', 'name' => 'Roti Bakar Klasik', 'price' => 16000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Roti tawar panggang dengan pilihan selai cokelat keju atau kaya butter.',
                'has_hot' => false, 'has_ice' => false,
                'allowed_addons' => [],
                'recipes' => [
                    ['item' => 'Roti Tawar & Selai', 'variant' => 'all', 'qty' => 1],
                ]
            ],
            [
                'cat' => 'camilan', 'name' => 'Dimsum Ayam / Udang', 'price' => 18000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Dimsum kukus daging ayam atau udang dengan saus sambal (isi 4 pcs).',
                'has_hot' => false, 'has_ice' => false,
                'type' => 'jadi',
                'stock_item' => 'Dimsum Ayam (Porsi)',
                'allowed_addons' => [],
                'recipes' => []
            ],
            [
                'cat' => 'camilan', 'name' => 'Cireng Bumbu Rujak', 'price' => 15000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Aci goreng renyah dengan sambal rujak gula merah pedas manis.',
                'has_hot' => false, 'has_ice' => false,
                'type' => 'jadi',
                'stock_item' => 'Cireng Rujak (Porsi)',
                'allowed_addons' => [],
                'recipes' => []
            ],
            [
                'cat' => 'camilan', 'name' => 'Butter Croissant', 'price' => 18000, 'ice_delta' => 0, 'featured' => false,
                'desc' => 'Pastry panggang berlapis mentega lembut dan renyah.',
                'has_hot' => false, 'has_ice' => false,
                'type' => 'jadi',
                'stock_item' => 'Butter Croissant',
                'allowed_addons' => [],
                'recipes' => []
            ],
        ];

        foreach ($menuList as $m) {
            $type = $m['type'] ?? 'racikan';
            $stockItemId = isset($m['stock_item']) ? $stockItems[$m['stock_item']]->id : null;

            $product = Product::firstOrCreate(['slug' => Str::slug($m['name'])], [
                'category_id' => $categories[$m['cat']]->id,
                'stock_item_id' => $stockItemId,
                'name' => $m['name'],
                'description' => $m['desc'],
                'price' => $m['price'],
                'type' => $type,
                'has_hot' => $m['has_hot'],
                'has_ice' => $m['has_ice'],
                'ice_price_delta' => $m['ice_delta'],
                'is_featured' => $m['featured'],
                'is_active' => true,
            ]);

            // Hubungkan Add-On yang diizinkan (Many-to-Many Pivot)
            if (!empty($m['allowed_addons'])) {
                $attachIds = [];
                foreach ($m['allowed_addons'] as $aoName) {
                    if (isset($addOnProducts[$aoName])) {
                        $attachIds[] = $addOnProducts[$aoName]->id;
                    }
                }
                $product->addOns()->sync($attachIds);
            }

            // Tambahkan resep bahan
            if ($type === 'racikan') {
                foreach ($m['recipes'] as $rcp) {
                    Recipe::firstOrCreate([
                        'product_id' => $product->id,
                        'stock_item_id' => $stockItems[$rcp['item']]->id,
                        'variant' => $rcp['variant'],
                    ], [
                        'qty_per_portion' => $rcp['qty'],
                    ]);
                }

                // Tambahkan resep kemasan otomatis untuk minuman racikan
                if ($m['has_ice']) {
                    Recipe::firstOrCreate(['product_id' => $product->id, 'stock_item_id' => $cupIce, 'variant' => 'ice'], ['qty_per_portion' => 1]);
                    Recipe::firstOrCreate(['product_id' => $product->id, 'stock_item_id' => $lidIce, 'variant' => 'ice'], ['qty_per_portion' => 1]);
                    Recipe::firstOrCreate(['product_id' => $product->id, 'stock_item_id' => $sedotan, 'variant' => 'ice'], ['qty_per_portion' => 1]);
                }
                if ($m['has_hot']) {
                    Recipe::firstOrCreate(['product_id' => $product->id, 'stock_item_id' => $cupHot, 'variant' => 'hot'], ['qty_per_portion' => 1]);
                    Recipe::firstOrCreate(['product_id' => $product->id, 'stock_item_id' => $lidHot, 'variant' => 'hot'], ['qty_per_portion' => 1]);
                }
            }
        }
    }
}
