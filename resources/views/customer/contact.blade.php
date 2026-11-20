<x-customer-layout>
    <x-slot:title>Kontak — JelangPulang Coffee</x-slot:title>

    <!-- Subtle decorative ambient background elements -->
    <div class="relative w-full overflow-hidden pointer-events-none">
        <div class="absolute -top-32 right-12 w-96 h-96 rounded-full bg-secondary-container/15 blur-3xl"></div>
        <div class="absolute top-96 -left-20 w-80 h-80 rounded-full bg-primary-fixed/20 blur-3xl"></div>
    </div>

    <!-- 1. Hero Header Section -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6 w-full relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high text-primary text-xs font-headline font-bold tracking-widest uppercase shadow-sm border border-outline-variant/30 mb-4">
            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
            Sapaan &amp; Pertanyaan
        </div>
        <h1 class="font-display text-4xl sm:text-6xl font-extrabold text-primary uppercase tracking-tight leading-[0.95] mb-4">
            Hubungi Kami.
        </h1>
        <p class="text-base sm:text-lg text-on-surface-variant max-w-xl mx-auto leading-relaxed">
            Butuh reservasi meja kerja, pemesanan katering kantor / hampers botolan, atau kerja sama khusus? Kirimkan sapaan Anda melalui formulir di bawah ini.
        </p>
    </section>

    <!-- 2. Formulir Pesan & Pertanyaan Interaktif (Centered) -->
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20 w-full relative z-10">
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
                <h2 class="font-headline text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Kirimkan Sapaan atau Pertanyaan</h2>
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
    </section>
</x-customer-layout>
