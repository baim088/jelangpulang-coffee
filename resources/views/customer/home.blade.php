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

    <!-- TENTANG KAMI -->
    <section id="tentang-kami" class="py-16 bg-surface-container-low border-y border-outline-variant/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Narasi -->
                <div class="lg:col-span-6 space-y-5">
                    <div class="flex items-center gap-2 text-secondary">
                        <span class="material-symbols-outlined text-[18px]">wb_twilight</span>
                        <span class="text-xs font-bold tracking-widest uppercase">Refleksi Senja</span>
                    </div>
                    <h2 class="font-headline text-3xl sm:text-4xl font-extrabold text-primary tracking-tight uppercase leading-tight">
                        Mengapa Kita Selalu Butuh Jeda di Jelang Sore?
                    </h2>
                    <div class="space-y-4 text-on-surface-variant text-base leading-relaxed">
                        <p>
                            Bagi kami, petang bukan penanda hari yang berakhir tergesa-gesa. Sore adalah waktu emas — sebuah jeda untuk menarik napas dan menata kembali pikiran yang terpecah.
                        </p>
                        <p>
                            Kami merancang profil sangrai <em>gentle medium-dark</em> yang rendah keasaman, bersahabat untuk perut yang lelah seharian bekerja. JelangPulang bukan sekadar gerai kafein, melainkan tempat berlabuh yang menawarkan seduhan jujur, santun, dan membumi.
                        </p>
                    </div>
                    <!-- Quote -->
                    <div class="bg-surface-container rounded-2xl p-5">
                        <p class="text-base text-primary italic leading-snug">
                            "Jangan bawa pulang penat kantor ke meja makan keluargamu. Tinggalkan sedikit bebanmu di sini bersama ampas kopi kami."
                        </p>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm font-semibold text-secondary">Rendra &amp; Alya — Inisiator JelangPulang</span>
                            <span class="material-symbols-outlined text-secondary opacity-60">coffee</span>
                        </div>
                    </div>
                </div>

                <!-- 4 Nilai Pondasi -->
                <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="p-5 rounded-2xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all space-y-3 border border-outline-variant/20">
                        <div class="flex items-center justify-between">
                            <span class="font-headline text-2xl text-secondary/60 font-bold">01</span>
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px]">local_fire_department</span>
                            </div>
                        </div>
                        <h3 class="font-headline text-lg font-bold text-primary uppercase">Sangrai Bersahabat</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">Profil sangrai lembut yang meminimalisir asam lambung. Nyaman dinikmati saat sore hari.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all space-y-3 border border-outline-variant/20">
                        <div class="flex items-center justify-between">
                            <span class="font-headline text-2xl text-secondary/60 font-bold">02</span>
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px]">handshake</span>
                            </div>
                        </div>
                        <h3 class="font-headline text-lg font-bold text-primary uppercase">Etika Petani Nusantara</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">Biji kopi langsung dari petani di Gayo, Toraja, Bajawa, dan Kintamani. Kemitraan adil tanpa tengkulak.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all space-y-3 border border-outline-variant/20">
                        <div class="flex items-center justify-between">
                            <span class="font-headline text-2xl text-secondary/60 font-bold">03</span>
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px]">chair</span>
                            </div>
                        </div>
                        <h3 class="font-headline text-lg font-bold text-primary uppercase">Ruang Tanpa Buru-Buru</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">Meja kayu solid, colokan di setiap penjuru, alunan lo-fi, dan pencahayaan temaram hangat 2700K.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all space-y-3 border border-outline-variant/20">
                        <div class="flex items-center justify-between">
                            <span class="font-headline text-2xl text-secondary/60 font-bold">04</span>
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px]">bedtime</span>
                            </div>
                        </div>
                        <h3 class="font-headline text-lg font-bold text-primary uppercase">Menu Mengerti Waktu</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">Cold brew segar untuk penat terik, dan tisane chamomile malam tanpa kafein agar tidurmu tetap berkualitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- KONTAK & LOKASI -->
    <section id="kontak" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold tracking-widest text-secondary uppercase">SINGGAH DI KEDAI</span>
            <h2 class="font-headline text-3xl sm:text-4xl font-extrabold text-primary uppercase tracking-tight">
                Hubungi Kami atau Kunjungi Langsung
            </h2>
            <p class="text-sm text-on-surface-variant mt-2">Reservasi meja, pemesanan katering, atau sekadar menanyakan ketersediaan menu favorit sore ini.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Info Kedai -->
            <div class="space-y-6">
                <!-- Alamat & Jam -->
                <div class="bg-surface-container-high rounded-2xl p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold tracking-widest text-primary uppercase">Titik Temu Kedai</span>
                        <span class="px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container text-xs font-bold">Buka Setiap Hari</span>
                    </div>
                    <div>
                        <h3 class="font-headline text-2xl font-bold text-primary">Kebayoran Baru</h3>
                        <p class="text-base font-semibold text-on-surface">Jl. Senja Kencana No. 42</p>
                        <p class="text-sm text-on-surface-variant">Jakarta Selatan 12160, Indonesia</p>
                        <p class="text-sm text-secondary italic mt-1">Patokan: 300 meter dari Stasiun MRT Blok A (Pintu Keluar Barat).</p>
                    </div>
                    <!-- Jam Operasional -->
                    <div class="bg-surface-container rounded-xl p-4 space-y-2">
                        <div class="flex items-center gap-2 text-primary">
                            <span class="material-symbols-outlined text-[20px]">timelapse</span>
                            <span class="font-semibold">Waktu Seduh &amp; Singgah</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface">Senin – Jumat</span>
                            <span class="font-semibold text-primary">{{ $storeHours }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-on-surface">Sabtu – Minggu</span>
                            <span class="font-semibold text-primary">{{ $storeHours }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kanal Kontak Cepat -->
                <div class="bg-surface-container-low rounded-2xl p-6 space-y-4">
                    <h3 class="font-headline text-xl font-bold text-primary uppercase">Kanal Percakapan Cepat</h3>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="flex items-start gap-3 p-3 rounded-xl hover:bg-surface-container-high transition-colors">
                        <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">chat</span>
                        </div>
                        <div>
                            <span class="font-semibold text-on-surface">WhatsApp Kedai</span>
                            <p class="text-sm text-on-surface-variant">+62 812-3456-7890</p>
                            <span class="text-xs text-secondary font-semibold">Reservasi meja, respon &lt; 10 menit</span>
                        </div>
                    </a>
                    <div class="flex items-start gap-3 p-3 rounded-xl">
                        <div class="w-10 h-10 rounded-full bg-secondary text-on-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <div>
                            <span class="font-semibold text-on-surface">Surat Elektronik</span>
                            <p class="text-sm text-primary">halo@jelangpulang.id</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 rounded-xl">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">public</span>
                        </div>
                        <div>
                            <span class="font-semibold text-on-surface">Kanal Sosial</span>
                            <p class="text-sm text-on-surface-variant">Instagram &amp; TikTok: <strong class="text-primary">@jelangpulang.jkt</strong></p>
                        </div>
                    </div>

                    <!-- Fasilitas -->
                    <div class="pt-2">
                        <span class="text-xs font-bold tracking-wider text-on-surface-variant uppercase block mb-2">Kenyamanan yang Tersedia</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach([
                                ['local_parking', 'Parkir Motor & Mobil'],
                                ['wifi', 'WiFi 100 Mbps'],
                                ['power', 'Colokan Setiap Meja'],
                                ['mosque', 'Musholla Bersih'],
                                ['ac_unit', 'Indoor Bebas Asap'],
                                ['yard', 'Teras Semi-Outdoor'],
                            ] as [$icon, $label])
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-on-surface text-xs font-semibold">
                                    <span class="material-symbols-outlined text-[16px] text-primary">{{ $icon }}</span> {{ $label }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="flex flex-col gap-6">
                <div class="relative overflow-hidden rounded-3xl bg-primary text-on-primary p-8 md:p-10 shadow-xl flex-1 flex flex-col justify-center">
                    <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-primary-container/40 blur-3xl pointer-events-none"></div>
                    <div class="absolute top-0 right-1/4 w-64 h-64 rounded-full bg-secondary/20 blur-2xl pointer-events-none"></div>
                    <div class="relative z-10 space-y-4">
                        <h2 class="font-headline text-3xl sm:text-4xl font-extrabold uppercase leading-tight tracking-tight">
                            Pintu Selalu Terbuka Sebelum Arah Pulangmu.
                        </h2>
                        <p class="text-sm leading-relaxed opacity-90 max-w-md">
                            Singgahlah sejenak di Jalan Senja Kencana No. 42. Biarkan bising kota surut pelan-pelan, sementara seduhan kopi hangat menemanimu melepas lelah.
                        </p>
                        <div class="flex flex-wrap gap-3 pt-2">
                            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-secondary-container text-on-secondary-container font-bold text-sm hover:opacity-90 transition-all shadow-md">
                                <span>Lihat Katalog Menu</span>
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                            <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-transparent hover:bg-on-primary/10 font-bold text-sm transition-all">
                                <span class="material-symbols-outlined text-[18px]">near_me</span>
                                <span>Petunjuk Arah</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Transportasi -->
                <div class="bg-surface-container rounded-2xl p-6 space-y-4">
                    <h3 class="font-headline text-xl font-bold text-primary uppercase">Pilihan Transportasi</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-[24px] mt-0.5">train</span>
                            <div>
                                <h4 class="font-semibold text-on-surface">MRT Jakarta (Blok A)</h4>
                                <p class="text-sm text-on-surface-variant">Jalan santai 3-4 menit dari Pintu Keluar B arah Panglima Polim.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-[24px] mt-0.5">directions_bus</span>
                            <div>
                                <h4 class="font-semibold text-on-surface">TransJakarta</h4>
                                <p class="text-sm text-on-surface-variant">Halte Jl. Panglima Polim Raya, turun tepat di gapura Senja Kencana.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-[24px] mt-0.5">two_wheeler</span>
                            <div>
                                <h4 class="font-semibold text-on-surface">Kendaraan Pribadi &amp; Ojol</h4>
                                <p class="text-sm text-on-surface-variant">Parkir motor teduh dan 6 slot mobil dengan juru parkir JelangPulang.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-customer-layout>
