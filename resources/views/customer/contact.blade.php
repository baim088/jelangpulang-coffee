<x-customer-layout>
    <x-slot:title>Kontak & Lokasi — JelangPulang Coffee</x-slot:title>

    <!-- Subtle decorative ambient background elements -->
    <div class="relative w-full overflow-hidden pointer-events-none">
        <div class="absolute -top-32 right-12 w-96 h-96 rounded-full bg-secondary-container/15 blur-3xl"></div>
        <div class="absolute top-96 -left-20 w-80 h-80 rounded-full bg-primary-fixed/20 blur-3xl"></div>
    </div>

    <!-- 1. Hero Header Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-8 w-full relative z-10">
        <div class="max-w-3xl flex flex-col items-start gap-3">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high text-primary text-xs font-headline font-bold tracking-widest uppercase shadow-sm border border-outline-variant/30">
                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                Titik Temu &amp; Sapaan
            </div>
            <h1 class="font-display text-4xl sm:text-6xl font-extrabold text-primary uppercase tracking-tight leading-[0.95]">
                Hubungi Kami <br class="hidden sm:inline">atau Singgah di Kedai.
            </h1>
            <p class="text-base sm:text-lg text-on-surface-variant max-w-2xl leading-relaxed">
                Butuh reservasi meja kerja, pemesanan katering kantor / hampers botolan, atau sekadar menanyakan ketersediaan menu favorit sore ini? Kami siap menyapa Anda sebelum arah pulang.
            </p>
        </div>
    </section>

    <!-- 2. Two-Column Main Layout: Info & Form -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Kiri: Informasi Kedai & Detail Kontak -->
            <div class="lg:col-span-5 flex flex-col gap-6">
                <!-- Card Utama: Kedai & Alamat -->
                <div class="bg-surface-container-high rounded-3xl p-6 sm:p-8 flex flex-col gap-5 shadow-sm border border-outline-variant/30">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold font-headline uppercase tracking-widest text-primary">Titik Temu Kedai</span>
                        <span class="px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container text-xs font-headline font-bold">Buka Setiap Hari</span>
                    </div>
                    <div class="space-y-1">
                        <h2 class="font-headline text-3xl font-extrabold text-primary tracking-tight">Kebayoran Baru</h2>
                        <p class="font-headline text-xl font-bold text-on-surface">{{ $storeAddress }}</p>
                        <p class="text-sm text-on-surface-variant">Jakarta Selatan 12160, Indonesia</p>
                        <p class="text-xs text-secondary italic pt-1 font-medium">
                            Patokan: 300 meter jalan kaki tenang dari Stasiun MRT Blok A (Pintu Keluar Barat).
                        </p>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="bg-surface-container rounded-2xl p-4 space-y-2 border border-outline-variant/20">
                        <div class="flex items-center gap-2 text-primary font-headline font-bold text-base">
                            <span class="material-symbols-outlined text-[20px]">timelapse</span>
                            <span>Waktu Seduh &amp; Singgah</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pt-1">
                            <span class="text-on-surface">Senin – Jumat</span>
                            <span class="font-bold text-primary">{{ $storeHours }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pb-1">
                            <span class="text-on-surface">Sabtu – Minggu</span>
                            <span class="font-bold text-primary">{{ $storeHours }}</span>
                        </div>
                        <p class="text-[11px] text-on-surface-variant/90 border-t border-outline-variant/20 pt-2">
                            *Golden hour sore (16.00 – 20.00 WIB) paling ramai dinikmati di area teras.
                        </p>
                    </div>

                    <!-- Tombol Navigasi Peta -->
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <a href="https://maps.google.com/?q={{ urlencode($storeAddress) }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 py-3 px-4 rounded-full bg-primary-container text-on-primary hover:bg-primary font-headline text-sm font-bold transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">map</span>
                            <span>Google Maps</span>
                        </a>
                        <a href="https://waze.com/ul?q={{ urlencode($storeAddress) }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 py-3 px-4 rounded-full bg-surface text-primary hover:bg-surface-container font-headline text-sm font-bold transition-all shadow-sm border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[18px]">navigation</span>
                            <span>Buka di Waze</span>
                        </a>
                    </div>
                </div>

                <!-- Card Fasilitas Kedai -->
                <div class="bg-surface-container-low rounded-3xl p-6 sm:p-8 flex flex-col gap-4 shadow-sm border border-outline-variant/30">
                    <h3 class="font-headline text-xl font-bold text-primary uppercase tracking-wide">Kenyamanan yang Tersedia</h3>
                    <p class="text-xs text-on-surface-variant">Fasilitas pendukung untuk menemani jeda sore Anda di kedai.</p>
                    <div class="flex flex-wrap gap-2 pt-1">
                        @foreach([
                            ['local_parking', 'Parkir Motor & Mobil'],
                            ['wifi', 'WiFi 100 Mbps'],
                            ['power', 'Colokan Setiap Meja'],
                            ['mosque', 'Musholla Bersih'],
                            ['ac_unit', 'Indoor Bebas Asap'],
                            ['yard', 'Teras Semi-Outdoor'],
                        ] as [$icon, $label])
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container text-on-surface text-xs font-medium border border-outline-variant/20">
                                <span class="material-symbols-outlined text-[16px] text-primary">{{ $icon }}</span> {{ $label }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Formulir Pesan & Pertanyaan Interaktif -->
            <div class="lg:col-span-7">
                <div class="bg-surface-container-lowest rounded-3xl p-6 sm:p-10 shadow-sm border border-outline-variant/30 relative" x-data="{
                    submitted: false,
                    loading: false,
                    name: '',
                    channel: '',
                    subject: '',
                    message: '',
                    submitForm() {
                        this.loading = true;
                        setTimeout(() => {
                            this.loading = false;
                            this.submitted = true;
                            this.name = '';
                            this.channel = '';
                            this.subject = '';
                            this.message = '';
                            setTimeout(() => { this.submitted = false; }, 6000);
                        }, 700);
                    }
                }">
                    <div class="mb-6">
                        <span class="text-xs font-headline font-bold text-secondary uppercase tracking-widest block mb-1">Formulir Langsung</span>
                        <h2 class="font-headline text-3xl font-extrabold text-primary tracking-tight">Kirimkan Sapaan atau Pertanyaan</h2>
                        <p class="text-sm text-on-surface-variant mt-1">
                            Masukan rasa, reservasi meja grup santai, atau kerja sama kopi botolan untuk agenda kantor Anda.
                        </p>
                    </div>

                    <form class="space-y-4" @submit.prevent="submitForm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="font-headline font-bold text-sm text-primary" for="contact-name">Nama Lengkap</label>
                                <input x-model="name" class="w-full px-4 py-3 rounded-2xl bg-surface-container-low text-on-surface text-sm placeholder:text-on-surface-variant/60 focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container transition-all border border-outline-variant/20" id="contact-name" placeholder="Contoh: Rian Anggara" required type="text">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="font-headline font-bold text-sm text-primary" for="contact-channel">Email / Nomor WhatsApp</label>
                                <input x-model="channel" class="w-full px-4 py-3 rounded-2xl bg-surface-container-low text-on-surface text-sm placeholder:text-on-surface-variant/60 focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container transition-all border border-outline-variant/20" id="contact-channel" placeholder="rian@email.com atau 0812xxxx" required type="text">
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-headline font-bold text-sm text-primary" for="contact-subject">Subjek Pesan</label>
                            <input x-model="subject" class="w-full px-4 py-3 rounded-2xl bg-surface-container-low text-on-surface text-sm placeholder:text-on-surface-variant/60 focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container transition-all border border-outline-variant/20" id="contact-subject" placeholder="Contoh: Booking Meja 6 Orang untuk Kamis Sore" required type="text">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="font-headline font-bold text-sm text-primary" for="contact-message">Detail Kebutuhan atau Pesan</label>
                            <textarea x-model="message" class="w-full px-4 py-3 rounded-2xl bg-surface-container-low text-on-surface text-sm placeholder:text-on-surface-variant/60 focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary-container transition-all resize-y border border-outline-variant/20" id="contact-message" placeholder="Tuliskan tanggal kunjungan, jumlah cangkir yang dibutuhkan, atau catatan khusus seduhan..." required rows="4"></textarea>
                        </div>

                        <!-- Feedback Success Alert -->
                        <div x-cloak x-show="submitted" x-transition class="p-4 rounded-2xl bg-surface-container-high text-primary text-sm flex items-center gap-3 border border-outline-variant/30">
                            <span class="material-symbols-outlined text-[22px] text-secondary">check_circle</span>
                            <span>Terima kasih! Pesan Anda telah kami terima. Barista JelangPulang akan membalas segera melalui WhatsApp atau email.</span>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <span class="text-xs text-on-surface-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-secondary">lock</span>
                                Data Anda dijaga dengan hormat dan aman.
                            </span>
                            <button class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-primary-container text-on-primary hover:bg-primary font-headline text-base font-bold transition-all duration-200 shadow-md hover:-translate-y-0.5 flex items-center justify-center gap-2" :disabled="loading" type="submit">
                                <span x-show="!loading">Kirim Pesan Sekarang</span>
                                <span x-cloak x-show="loading">Mengirimkan...</span>
                                <span class="material-symbols-outlined text-[18px]">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Section Peta Lokasi & Suasana Menuju Kedai -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full relative z-10">
        <div class="bg-surface-container-low rounded-3xl p-6 sm:p-10 overflow-hidden shadow-sm border border-outline-variant/30">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="text-xs font-headline font-bold text-secondary uppercase tracking-widest block mb-1">Rute &amp; Aksesibilitas</span>
                    <h2 class="font-headline text-3xl font-extrabold text-primary tracking-tight">Menuju Sudut Tenang JelangPulang</h2>
                </div>
                <p class="text-sm text-on-surface-variant max-w-md">
                    Berada di persimpangan teduh Kebayoran Baru, dapat diakses santai dengan jalan kaki sejuk di bawah pepohonan rindang sore.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                <!-- Visual Banner Kedai / Peta -->
                <div class="lg:col-span-8 relative min-h-[340px] rounded-2xl overflow-hidden shadow-sm">
                    <img class="w-full h-full object-cover min-h-[340px]" src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1200&auto=format&fit=crop" alt="Kedai Kopi JelangPulang">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 sm:right-auto bg-surface/95 backdrop-blur-md p-4 rounded-2xl shadow-md flex items-center gap-3 border border-outline-variant/30">
                        <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]">coffee</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-headline text-lg font-bold text-primary leading-tight">Kedai Kopi JelangPulang</span>
                            <span class="text-xs text-on-surface-variant">{{ $storeAddress }}</span>
                        </div>
                    </div>
                </div>

                <!-- Panduan Akses Transportasi Publik & Parkir -->
                <div class="lg:col-span-4 flex flex-col justify-between gap-4 bg-surface-container rounded-2xl p-6 border border-outline-variant/20">
                    <h3 class="font-headline text-xl font-bold text-primary uppercase tracking-wide">Pilihan Transportasi</h3>
                    <div class="space-y-4 flex-1">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-[24px] mt-0.5">train</span>
                            <div>
                                <h4 class="font-headline font-bold text-base text-on-surface">MRT Jakarta (Blok A)</h4>
                                <p class="text-xs text-on-surface-variant leading-snug">
                                    Turun di Stasiun Blok A, gunakan Pintu Keluar B (arah Panglima Polim), lalu jalan santai 3-4 menit ke arah timur.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-[24px] mt-0.5">directions_bus</span>
                            <div>
                                <h4 class="font-headline font-bold text-base text-on-surface">TransJakarta &amp; Mikrotrans</h4>
                                <p class="text-xs text-on-surface-variant leading-snug">
                                    Halte terdekat: Jl. Panglima Polim Raya (Koridor 1E / Rute JAK.31). Turun tepat di gapura Senja Kencana.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-[24px] mt-0.5">two_wheeler</span>
                            <div>
                                <h4 class="font-headline font-bold text-base text-on-surface">Kendaraan Pribadi &amp; Ojol</h4>
                                <p class="text-xs text-on-surface-variant leading-snug">
                                    Tersedia area parkir motor teduh dan slot parkir 6 mobil dengan juru parkir ramah berseragam JelangPulang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-customer-layout>
