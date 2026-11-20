<x-customer-layout>
    <x-slot:title>JelangPulang — Satu gelas sebelum arah pulang</x-slot:title>

    <!-- HERO SECTION -->
    <section class="relative w-full overflow-hidden bg-surface-bright pt-8 pb-16 lg:pt-16 lg:pb-24 border-b border-outline-variant/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Status Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high text-primary mb-6 shadow-sm border border-outline-variant/30">
                <span class="w-2.5 h-2.5 rounded-full {{ $isOpen ? 'bg-emerald-600 animate-pulse' : 'bg-secondary' }}"></span>
                <span class="font-headline font-bold text-sm tracking-wider uppercase">
                    {{ $isOpen ? 'KEDAI BUKA SEKARANG' : 'KEDAI BUKA SORE NANTI' }} — {{ $storeHours }}
                </span>
                <span class="text-outline-variant font-bold">•</span>
                <span class="text-xs text-on-surface-variant font-medium">{{ $storeAddress }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Text Column -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="space-y-1">
                        <span class="text-xs font-bold tracking-widest text-secondary uppercase">RITUAL JEDA SORE</span>
                        <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-extrabold text-primary tracking-tight uppercase leading-none">
                            SATU GELAS SEBELUM ARAH PULANG.
                        </h1>
                    </div>
                    <p class="text-base sm:text-lg text-on-surface-variant max-w-xl leading-relaxed">
                        Singgahi soremu dengan racikan biji kopi Nusantara terpilih, semerbak aroma sangrai yang menenangkan, dan jeda sejenak untuk melaraskan rasa sebelum kembali ke rumah.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center gap-2 font-headline text-lg font-bold px-8 py-3.5 rounded-full bg-primary-container text-on-primary hover:bg-primary transition-all shadow-[0_8px_24px_rgba(92,35,8,0.14)] active:scale-95">
                            <span>Pesan Sekarang</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                        <a href="#menu-unggulan" class="inline-flex items-center justify-center gap-2 font-headline text-lg font-bold px-7 py-3.5 rounded-full bg-surface-container text-primary hover:bg-surface-container-high transition-all">
                            <span>Menu Unggulan</span>
                            <span class="material-symbols-outlined text-[18px]">expand_more</span>
                        </a>
                    </div>

                    <!-- Micro Metrics -->
                    <div class="pt-6 grid grid-cols-3 gap-6 max-w-md border-t border-outline-variant/30">
                        <div>
                            <span class="font-headline text-2xl font-bold text-primary">100%</span>
                            <p class="text-xs text-on-surface-variant">Biji Kopi Lokal</p>
                        </div>
                        <div>
                            <span class="font-headline text-2xl font-bold text-primary">QRIS</span>
                            <p class="text-xs text-on-surface-variant">Self-Order Cepat</p>
                        </div>
                        <div>
                            <span class="font-headline text-2xl font-bold text-primary">Fresh</span>
                            <p class="text-xs text-on-surface-variant">Racik Langsung</p>
                        </div>
                    </div>
                </div>

                <!-- Visual Banner -->
                <div class="lg:col-span-5 relative flex justify-center items-center">
                    <div class="relative w-full max-w-sm aspect-[4/5] rounded-3xl overflow-hidden bg-surface-container shadow-2xl border-4 border-surface-container-lowest">
                        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop" alt="Secangkir kopi senja JelangPulang">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
                            <span class="text-xs font-bold tracking-widest text-secondary-fixed uppercase">Signature Kedai</span>
                            <h3 class="font-headline text-2xl font-bold">Kopi Jelang Pulang</h3>
                            <p class="text-xs text-white/80 mt-1">Espresso, susu segar, aren gurih & sea salt cream.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MENU UNGGULAN -->
    <section id="menu-unggulan" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold tracking-widest text-secondary uppercase">REKOMENDASI SENJA</span>
                <h2 class="font-headline text-3xl sm:text-4xl font-extrabold text-primary tracking-tight uppercase">
                    Sajian Unggulan Hari Ini
                </h2>
                <p class="text-sm text-on-surface-variant mt-1">Racikan favorit yang paling banyak dicari saat jam pulang kantor.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1 text-sm font-bold text-primary hover:text-primary-container transition-colors">
                <span>Lihat Semua Katalog</span>
                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-surface-container-lowest rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between border border-outline-variant/30">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-container text-xs font-bold font-headline">
                                {{ $product->category->name }}
                            </span>
                            @if($product->has_ice && $product->has_hot)
                                <span class="text-[11px] text-on-surface-variant font-medium">Hot / Ice</span>
                            @elseif($product->has_ice)
                                <span class="text-[11px] text-secondary font-medium">Dingin (Ice)</span>
                            @else
                                <span class="text-[11px] text-primary font-medium">Hangat (Hot)</span>
                            @endif
                        </div>
                        <h3 class="font-headline text-2xl font-bold text-primary">{{ $product->name }}</h3>
                        <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed line-clamp-2">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-on-surface-variant block uppercase tracking-wider">Harga Mulai</span>
                            <span class="font-price text-2xl text-secondary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('catalog.index', ['kategori' => $product->category->slug]) }}" class="px-4 py-2 rounded-full bg-primary-container text-on-primary font-headline text-sm font-bold hover:bg-primary transition-all">
                            Pesan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- CARA PESAN CEPAT SECTION -->
    <section class="py-12 bg-surface-container-low border-y border-outline-variant/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold tracking-widest text-secondary uppercase">TANPA ANTRE</span>
                <h2 class="font-headline text-3xl font-extrabold text-primary uppercase">3 Langkah Pesan Mandiri</h2>
                <p class="text-sm text-on-surface-variant mt-1">Duduk di meja, pilih pesanan dari ponsel, dan bayar lewat QRIS dalam sekejap.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm text-center space-y-3 border border-outline-variant/20">
                    <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary mx-auto flex items-center justify-center font-headline text-xl font-bold">1</div>
                    <h3 class="font-headline text-xl font-bold text-primary">Pilih Menu & Meja</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Buka katalog, sesuaikan takaran manis, es, dan add-on favorit Anda.</p>
                </div>

                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm text-center space-y-3 border border-outline-variant/20">
                    <div class="w-12 h-12 rounded-full bg-secondary text-white mx-auto flex items-center justify-center font-headline text-xl font-bold">2</div>
                    <h3 class="font-headline text-xl font-bold text-primary">Scan QRIS & Bukti</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Lakukan transfer QRIS ShopeePay lalu unggah tangkapan layar bukti bayar.</p>
                </div>

                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm text-center space-y-3 border border-outline-variant/20">
                    <div class="w-12 h-12 rounded-full bg-primary text-white mx-auto flex items-center justify-center font-headline text-xl font-bold">3</div>
                    <h3 class="font-headline text-xl font-bold text-primary">Diproses & Diantar</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Pantau status secara langsung di layar ponsel hingga minuman siap dinikmati.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FASILITAS & KENYAMANAN KEDAI -->
    <section class="py-14 bg-surface border-b border-outline-variant/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-headline font-bold tracking-widest text-secondary uppercase">SUASANA KEDAI</span>
                <h2 class="font-headline text-3xl font-extrabold text-primary uppercase">Kenyamanan yang Tersedia</h2>
                <p class="text-sm text-on-surface-variant mt-1">Fasilitas pendukung untuk menemani jeda sore Anda yang tenang sebelum arah pulang.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach([
                    ['local_parking', 'Parkir Motor & Mobil', 'Area teduh & aman'],
                    ['wifi', 'WiFi 100 Mbps', 'Koneksi stabil'],
                    ['power', 'Colokan Setiap Meja', 'Daya kerja terpenuhi'],
                    ['mosque', 'Musholla Bersih', 'Ibadah tenang'],
                    ['ac_unit', 'Indoor Bebas Asap', 'Sejuk & nyaman'],
                    ['yard', 'Teras Semi-Outdoor', 'Angin sejuk sore'],
                ] as [$icon, $label, $sub])
                    <div class="p-4 rounded-2xl bg-surface-container-low hover:bg-surface-container transition-all flex flex-col items-center text-center gap-2 border border-outline-variant/20 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                        </div>
                        <div>
                            <span class="font-headline font-bold text-sm text-primary block leading-tight">{{ $label }}</span>
                            <span class="text-[11px] text-on-surface-variant mt-0.5 block">{{ $sub }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="text-center space-y-2 mb-8">
                <span class="text-xs font-headline font-bold uppercase tracking-widest text-secondary">TANYA JAWAB SINGKAT</span>
                <h2 class="font-headline text-3xl font-extrabold text-primary uppercase tracking-tight">Kerap Ditanyakan Sebelum Singgah</h2>
                <p class="text-sm text-on-surface-variant max-w-lg mx-auto">Segala hal yang perlu kamu ketahui agar singgah soremu terasa lebih tenang dan nyaman.</p>
            </div>
            <div class="space-y-3">
                <details class="group bg-surface-container-lowest rounded-2xl p-5 shadow-sm border border-outline-variant/20">
                    <summary class="flex items-center justify-between cursor-pointer list-none font-headline font-bold text-base text-primary">
                        <span>Apakah bisa reservasi meja untuk kerja kelompok atau meeting santai?</span>
                        <span class="material-symbols-outlined text-primary group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="mt-3 text-sm text-on-surface-variant leading-relaxed">
                        Tentu bisa. Kami menyediakan area meja panjang komunal yang dapat direservasi minimal 2 jam sebelumnya via WhatsApp tanpa biaya sewa tambahan.
                    </p>
                </details>
                <details class="group bg-surface-container-lowest rounded-2xl p-5 shadow-sm border border-outline-variant/20">
                    <summary class="flex items-center justify-between cursor-pointer list-none font-headline font-bold text-base text-primary">
                        <span>Apakah tersedia colokan listrik dan koneksi internet yang stabil?</span>
                        <span class="material-symbols-outlined text-primary group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="mt-3 text-sm text-on-surface-variant leading-relaxed">
                        Ya, setiap sudut meja dilengkapi stopkontak ganda dan fasilitas Wi-Fi berkecepatan hingga 100 Mbps, cocok untuk menyelesaikan pekerjaan sebelum melanjutkan arah pulang.
                    </p>
                </details>
                <details class="group bg-surface-container-lowest rounded-2xl p-5 shadow-sm border border-outline-variant/20">
                    <summary class="flex items-center justify-between cursor-pointer list-none font-headline font-bold text-base text-primary">
                        <span>Bagaimana cara memesan kopi botolan untuk acara kantor atau hampers?</span>
                        <span class="material-symbols-outlined text-primary group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="mt-3 text-sm text-on-surface-variant leading-relaxed">
                        Anda dapat langsung menghubungi WhatsApp kedai kami untuk pemesanan botolan 250ml hingga 1 Liter (H-1 acara) dengan diskon khusus volume besar.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA STRIP PENUTUP (FULL WIDTH) -->
    <section class="w-full bg-primary text-on-primary py-14 sm:py-20 relative overflow-hidden border-b border-outline-variant/30">
        <!-- Ambient Glow Accents -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-primary-container/40 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-secondary/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center flex flex-col items-center space-y-4">
            <span class="text-xs font-headline font-bold tracking-widest text-secondary-fixed uppercase">RITUAL SENJA KEDAI</span>
            <h2 class="font-headline text-3xl sm:text-4xl md:text-5xl font-extrabold uppercase leading-tight tracking-tight">
                Pintu Selalu Terbuka Sebelum Arah Pulangmu.
            </h2>
            <p class="text-sm sm:text-base text-primary-fixed leading-relaxed opacity-95 max-w-xl">
                Singgahlah sejenak di Jalan Senja Kencana No. 42. Biarkan bising kota surut pelan-pelan di ambang pintu, ditemani seduhan kopi hangat pereda lelah.
            </p>
            <div class="flex flex-wrap justify-center items-center gap-4 pt-3">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-secondary-container text-on-secondary-container font-headline font-bold text-base hover:opacity-90 transition-all shadow-md active:scale-95">
                    <span>Pesan Sekarang</span>
                    <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-surface-container text-primary font-headline font-bold text-base hover:bg-surface-container-high transition-all active:scale-95">
                    <span>Kisah di Balik Kedai</span>
                    <span class="material-symbols-outlined text-[18px]">auto_stories</span>
                </a>
            </div>
        </div>
    </section>

</x-customer-layout>
