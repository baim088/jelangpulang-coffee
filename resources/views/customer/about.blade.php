<x-customer-layout>
    <x-slot:title>Tentang Kami — JelangPulang Coffee</x-slot:title>

    <!-- Top Ambient Glow Decor -->
    <div class="relative w-full overflow-hidden pointer-events-none">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[340px] bg-secondary-container/15 rounded-full blur-[100px]"></div>
    </div>

    <!-- 1. HERO HEADER EDITORIAL & TIMELINE -->
    <section class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        <div class="flex flex-col w-full max-w-5xl mx-auto">
            <!-- Badge Pill -->
            <div class="inline-flex self-start items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high text-primary text-xs font-headline font-bold tracking-widest uppercase mb-6 shadow-sm border border-outline-variant/30">
                <span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse"></span>
                Kisah di Balik Secangkir Jeda
            </div>

            <!-- Main Headline Display -->
            <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-extrabold text-primary uppercase leading-[0.95] tracking-tight mb-6">
                Ruang Transisi dari <br class="hidden sm:inline">
                <span class="text-secondary italic font-normal">Hiruk-Pikuk</span> Menuju Rumah.
            </h1>

            <!-- Subtitle Story -->
            <p class="text-base sm:text-lg text-on-surface-variant max-w-2xl leading-relaxed mb-8">
                JelangPulang lahir di antara desing klakson sore Jakarta dan kebutuhan sederhana untuk bernapas sejenak. Sebelum menembus lelahnya perjalanan pulang, kami menyiapkan secangkir kopi hangat sebagai pemisah antara beban hari ini dan kedamaian rumah.
            </p>

            <!-- Timeline Component -->
            <div class="w-full pt-6 pb-8 mt-6 border-t border-outline-variant/30">
                <div class="flex flex-col items-center text-center mb-10 pt-4">
                    <div class="inline-block px-4 py-1 rounded-full bg-primary-container text-on-primary font-headline text-xs font-bold uppercase tracking-wider mb-2 shadow-sm">
                        OUR STORY
                    </div>
                    <h2 class="font-headline text-3xl sm:text-4xl font-extrabold text-primary uppercase tracking-tight leading-none">
                        Perjalanan JelangPulang
                    </h2>
                </div>

                <!-- Vertical Milestone Timeline Track -->
                <div class="relative max-w-4xl mx-auto py-2">
                    <div class="absolute left-1/2 top-4 bottom-8 -translate-x-1/2 w-[2px] bg-primary-container/30 pointer-events-none hidden md:block"></div>

                    <!-- Timeline Item 1: 2016 -->
                    <div class="relative flex flex-col md:flex-row items-center mb-10">
                        <div class="w-full md:w-1/2 md:pr-10 text-left md:text-right">
                            <span class="font-headline text-xl text-primary font-bold block mb-1">2016</span>
                            <h3 class="font-headline text-2xl font-bold text-primary uppercase leading-tight tracking-tight mb-2">
                                Kedai Pertama Dibuka
                            </h3>
                            <p class="text-sm text-on-surface-variant leading-relaxed max-w-md md:ml-auto">
                                Bermula dari keyakinan sederhana: secangkir seduhan jujur di penghujung hari dapat mengubah lelah perjalanan menjadi momen yang bermakna.
                            </p>
                        </div>
                        <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-primary-container ring-4 ring-surface items-center justify-center z-10"></div>
                        <div class="w-full md:w-1/2 md:pl-10 mt-2 md:mt-0"></div>
                    </div>

                    <!-- Timeline Item 2: 2022 -->
                    <div class="relative flex flex-col md:flex-row items-center mb-10">
                        <div class="w-full md:w-1/2 md:pr-10 hidden md:block"></div>
                        <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-primary-container ring-4 ring-surface items-center justify-center z-10"></div>
                        <div class="w-full md:w-1/2 md:pl-10 text-left">
                            <span class="font-headline text-xl text-primary font-bold block mb-1">2022</span>
                            <h3 class="font-headline text-2xl font-bold text-primary uppercase leading-tight tracking-tight mb-2">
                                Ruang Bertemu &amp; Bersandar
                            </h3>
                            <p class="text-sm text-on-surface-variant leading-relaxed max-w-md">
                                JelangPulang bertransformasi menjadi ruang teduh tempat kopi berkualitas, suasana hangat, dan percakapan bersahaja melebur setiap petang.
                            </p>
                        </div>
                    </div>

                    <!-- Timeline Item 3: 2026 -->
                    <div class="relative flex flex-col md:flex-row items-center">
                        <div class="w-full md:w-1/2 md:pr-10 text-left md:text-right">
                            <span class="font-headline text-xl text-primary font-bold block mb-1">2026</span>
                            <h3 class="font-headline text-2xl font-bold text-primary uppercase leading-tight tracking-tight mb-2">
                                Terus Tumbuh Bersama
                            </h3>
                            <p class="text-sm text-on-surface-variant leading-relaxed max-w-md md:ml-auto">
                                Hari ini, JelangPulang terus menghadirkan ritual jeda yang tulus, menghubungkan para pejuang pulang dan petani kopi nusantara melalui self-order yang cepat dan nyaman.
                            </p>
                        </div>
                        <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-primary-container ring-4 ring-surface items-center justify-center z-10"></div>
                        <div class="w-full md:w-1/2 md:pl-10 mt-2 md:mt-0"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SECTION FILOSOFI & CERITA PENDIRI -->
    <section class="w-full bg-surface-container-low py-16 border-y border-outline-variant/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Kolom Kiri: Narasi Editorial -->
                <div class="lg:col-span-6 flex flex-col space-y-5">
                    <div class="flex items-center gap-2 text-secondary font-headline text-xs font-bold uppercase tracking-widest">
                        <span class="material-symbols-outlined text-[18px]">wb_twilight</span>
                        <span>Refleksi Senja</span>
                    </div>
                    <h2 class="font-headline text-3xl sm:text-4xl font-extrabold text-primary leading-tight uppercase">
                        Mengapa Kita Selalu Butuh Jeda di Jelang Sore?
                    </h2>
                    <div class="space-y-4 text-on-surface-variant text-base leading-relaxed">
                        <p>
                            Bagi kami, petang bukan penanda hari yang berakhir tergesa-gesa dengan kelelahan yang dipaksakan. Sore adalah waktu emas, saat matahari mulai condong dan bayang-bayang memanjang—sebuah jeda untuk menarik napas dan menata kembali pikiran yang terpecah.
                        </p>
                        <p>
                            Kami merancang profil sangrai <em>gentle medium-dark</em> yang rendah keasaman, bersahabat untuk perut yang lelah seharian bekerja. JelangPulang bukan sekadar gerai kafein tinggi, melainkan tempat berlabuh yang menawarkan seduhan yang jujur, santun, dan membumi.
                        </p>
                    </div>
                    <!-- Handwritten Quote -->
                    <div class="bg-surface-container rounded-2xl p-5 border border-outline-variant/30">
                        <p class="text-base text-primary italic leading-snug">
                            “Jangan bawa pulang penat kantor ke meja makan keluargamu. Tinggalkan sedikit bebanmu di sini bersama ampas kopi kami.”
                        </p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm font-semibold text-secondary">Rendra &amp; Alya — Inisiator JelangPulang</span>
                            <span class="material-symbols-outlined text-secondary opacity-60">coffee</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Bento Visual Grid -->
                <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-5">
                        <div class="relative rounded-3xl overflow-hidden shadow-sm aspect-[3/4] bg-surface-container">
                            <img class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=800&auto=format&fit=crop" alt="Ritual seduh manual JelangPulang">
                            <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent text-white">
                                <span class="text-xs uppercase tracking-wider block font-bold text-secondary-fixed">Ritual Manual Brew</span>
                                <span class="font-headline text-lg font-bold">Seduhan Lambat Berirama</span>
                            </div>
                        </div>
                        <div class="p-5 rounded-3xl bg-secondary-container/20 flex flex-col justify-center items-center text-center border border-outline-variant/20">
                            <span class="material-symbols-outlined text-primary text-[32px] mb-2">nest_eco_leaf</span>
                            <span class="font-headline text-xl font-bold text-primary leading-tight">Sangrai Bersahaja</span>
                            <p class="text-xs text-on-surface-variant mt-1">Mengutamakan kenyamanan tubuh dan kehangatan lambung</p>
                        </div>
                    </div>
                    <div class="space-y-5 sm:pt-8">
                        <div class="p-5 rounded-3xl bg-surface-container flex flex-col justify-between border border-outline-variant/30">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-secondary uppercase tracking-wider">Suasana</span>
                                <span class="material-symbols-outlined text-primary text-[20px]">menu_book</span>
                            </div>
                            <p class="font-headline text-lg font-bold text-primary">Deretan bacaan kurasi, kursi rotan empuk, dan aroma seduhan segar.</p>
                        </div>
                        <div class="relative rounded-3xl overflow-hidden shadow-sm aspect-[3/4] bg-surface-container">
                            <img class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?q=80&w=800&auto=format&fit=crop" alt="Sudut tenang kedai JelangPulang">
                            <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent text-white">
                                <span class="text-xs uppercase tracking-wider block font-bold text-secondary-fixed">Sudut Tenang</span>
                                <span class="font-headline text-lg font-bold">Tanpa Riuh &amp; Tergesa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. SECTION 4 NILAI UTAMA JELANGPULANG -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold text-secondary uppercase tracking-widest block mb-1">Komitmen Kami</span>
                <h2 class="font-headline text-3xl sm:text-4xl font-extrabold text-primary uppercase leading-tight">
                    Empat Pondasi Tiap Cangkir
                </h2>
            </div>
            <p class="text-sm text-on-surface-variant max-w-md">
                Setiap butir biji kopi dan tata ruang kedai kami dibangun atas rasa hormat pada tubuh penikmatnya dan tanah tempat tanaman itu berakar.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 01 -->
            <div class="p-6 rounded-3xl bg-surface-container-low hover:bg-surface-container transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 shadow-sm border border-outline-variant/20">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-headline text-2xl text-secondary/60 font-bold group-hover:text-primary transition-colors">01</span>
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">local_fire_department</span>
                        </div>
                    </div>
                    <h3 class="font-headline text-xl font-bold text-primary uppercase mb-2 leading-snug">
                        Sangrai Bersahabat
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Profil sangrai lembut yang meminimalisir asam lambung (low acidity). Diseduh agar tetap nyaman dinikmati saat sore hari bahkan saat perut belum terisi makan malam.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-outline-variant/20">
                    <span class="text-xs text-secondary font-semibold uppercase tracking-wider">Gentle Roast Profile</span>
                </div>
            </div>
            <!-- Card 02 -->
            <div class="p-6 rounded-3xl bg-surface-container-low hover:bg-surface-container transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 shadow-sm border border-outline-variant/20">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-headline text-2xl text-secondary/60 font-bold group-hover:text-primary transition-colors">02</span>
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">handshake</span>
                        </div>
                    </div>
                    <h3 class="font-headline text-xl font-bold text-primary uppercase mb-2 leading-snug">
                        Etika Petani Nusantara
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Biji kopi bersumber langsung dari petani rakyat di Gayo, Toraja, Bajawa, dan Kintamani. Kemitraan adil transparan tanpa potongan rantai tengkulak yang merugikan.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-outline-variant/20">
                    <span class="text-xs text-secondary font-semibold uppercase tracking-wider">Direct Origin Sourcing</span>
                </div>
            </div>
            <!-- Card 03 -->
            <div class="p-6 rounded-3xl bg-surface-container-low hover:bg-surface-container transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 shadow-sm border border-outline-variant/20">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-headline text-2xl text-secondary/60 font-bold group-hover:text-primary transition-colors">03</span>
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">chair</span>
                        </div>
                    </div>
                    <h3 class="font-headline text-xl font-bold text-primary uppercase mb-2 leading-snug">
                        Ruang Tanpa Buru-Buru
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Meja kayu solid yang lebar, colokan daya di setiap penjuru, alunan lo-fi tempo lambat, dan pencahayaan temaram hangat 2700K yang meredakan ketegangan mata.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-outline-variant/20">
                    <span class="text-xs text-secondary font-semibold uppercase tracking-wider">Unrushed Atmosphere</span>
                </div>
            </div>
            <!-- Card 04 -->
            <div class="p-6 rounded-3xl bg-surface-container-low hover:bg-surface-container transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 shadow-sm border border-outline-variant/20">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-headline text-2xl text-secondary/60 font-bold group-hover:text-primary transition-colors">04</span>
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">bedtime</span>
                        </div>
                    </div>
                    <h3 class="font-headline text-xl font-bold text-primary uppercase mb-2 leading-snug">
                        Menu Mengerti Waktu
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Pilihan cold brew segar untuk melepas penat terik aspal, serta racikan tisane chamomile rempah malam hari tanpa kafein agar istirahat tidurmu tetap berkualitas.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-outline-variant/20">
                    <span class="text-xs text-secondary font-semibold uppercase tracking-wider">Time-Attuned Beverages</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. SECTION SUDUT KEDAI & BARISTA -->
    <section class="w-full bg-surface-container py-16 border-t border-outline-variant/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mb-10">
                <span class="text-xs font-bold text-secondary uppercase tracking-widest block mb-1">Menjelajahi Kedai</span>
                <h2 class="font-headline text-3xl sm:text-4xl font-extrabold text-primary uppercase leading-tight">
                    Sudut Nyaman Untuk Setiap Alasan Singgah
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Feature 1: Slow Bar -->
                <div class="group rounded-3xl overflow-hidden bg-surface-container-lowest flex flex-col shadow-sm border border-outline-variant/20">
                    <div class="relative h-60 overflow-hidden bg-surface-container-high">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=800&auto=format&fit=crop" alt="Slow Bar & Seduh Manual">
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-surface/90 backdrop-blur-sm text-primary text-xs font-headline font-bold uppercase tracking-wider">
                            Slow Bar &amp; Seduh Manual
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-headline text-xl font-bold text-primary mb-1">Diskusi Rasa Bersama Barista</h3>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Duduk tepat di seberang meja seduh, pilih single origin favoritmu, dan simak cerita di balik setiap gram bubuk kopi yang diseduh santai.
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-secondary text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]">coffee_maker</span>
                            <span>Pilihan Single Origin Nusantara</span>
                        </div>
                    </div>
                </div>
                <!-- Feature 2: Work & Read Nook -->
                <div class="group rounded-3xl overflow-hidden bg-surface-container-lowest flex flex-col shadow-sm border border-outline-variant/20">
                    <div class="relative h-60 overflow-hidden bg-surface-container-high">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?q=80&w=800&auto=format&fit=crop" alt="Ruang Baca & Kerja">
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-surface/90 backdrop-blur-sm text-primary text-xs font-headline font-bold uppercase tracking-wider">
                            Ruang Baca &amp; Kerja
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-headline text-xl font-bold text-primary mb-1">Ketenangan Menyelesaikan Tugas</h3>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Dirancang khusus bagi pekerja mandiri yang membutuhkan fokus menjelang jam kerja berakhir, lengkap dengan wifi andal dan stopkontak berlimpah.
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-secondary text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]">wifi</span>
                            <span>Daya &amp; Internet Berkecepatan Tinggi</span>
                        </div>
                    </div>
                </div>
                <!-- Feature 3: Outdoor Sunset Porch -->
                <div class="group rounded-3xl overflow-hidden bg-surface-container-lowest flex flex-col shadow-sm border border-outline-variant/20">
                    <div class="relative h-60 overflow-hidden bg-surface-container-high">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=800&auto=format&fit=crop" alt="Teras Senja Terbuka">
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-surface/90 backdrop-blur-sm text-primary text-xs font-headline font-bold uppercase tracking-wider">
                            Teras Senja Terbuka
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-headline text-xl font-bold text-primary mb-1">Menatap Jingga Langit Petang</h3>
                            <p class="text-xs text-on-surface-variant leading-relaxed">
                                Area terbuka beratap dedaunan rindang untuk menikmati hembusan angin sore seraya menyeruput Kopi Jelang Pulang bersama kawan.
                            </p>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-secondary text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]">nature_people</span>
                            <span>Smoking Friendly &amp; Suasana Asri</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barista Spotlight Quote -->
            <div class="p-6 rounded-3xl bg-surface-container-highest flex flex-col md:flex-row items-center gap-6 border border-outline-variant/30">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden shrink-0 shadow-md border-2 border-surface-container-lowest">
                    <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop" alt="Bagas Satria Head Roaster JelangPulang">
                </div>
                <div class="space-y-1 text-center md:text-left">
                    <div class="text-xs font-bold uppercase tracking-widest text-secondary">Pesan Dari Head Roaster</div>
                    <p class="text-base text-primary italic leading-relaxed">
                        “Kami tak ingin menciptakan kopi yang mengintimidasi dengan jargon rumit. Kami hanya ingin secangkir seduhan yang terasa seperti sapaan ramah: 'Hari yang panjang, ya? Duduklah dulu, nikmati ini.'”
                    </p>
                    <span class="text-xs font-semibold text-on-surface-variant block">— Bagas Satria, Kurator Biji Kopi JelangPulang</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. BANNER AJAKAN SINGGAH -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-primary text-on-primary p-8 md:p-16 shadow-xl">
            <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-primary-container/40 blur-3xl pointer-events-none"></div>
            <div class="absolute top-0 right-1/4 w-64 h-64 rounded-full bg-secondary/20 blur-2xl pointer-events-none"></div>
            <div class="relative z-10 max-w-3xl space-y-4">
                <span class="text-xs font-bold tracking-widest text-secondary-fixed uppercase">PINTU SELALU TERBUKA</span>
                <h2 class="font-display text-4xl sm:text-5xl font-extrabold uppercase leading-tight tracking-tight">
                    Sebelum Arah Pulangmu, Singgahlah Sejenak.
                </h2>
                <p class="text-sm sm:text-base text-primary-fixed leading-relaxed max-w-xl">
                    Singgahlah di {{ $storeAddress }}. Biarkan bising kota surut pelan-pelan di ambang pintu, sementara seduhan kopi hangat kami menemanimu melepas lelah.
                </p>
                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog.index') }}" class="bg-secondary-container text-on-secondary-container hover:bg-secondary-fixed font-headline font-bold text-base px-6 py-3.5 rounded-full transition-all duration-200 shadow-md inline-flex items-center gap-2">
                        <span>Lihat Katalog Menu</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                    <a href="{{ route('contact') }}" class="bg-transparent hover:bg-on-primary/10 text-on-primary font-headline font-bold text-base px-6 py-3.5 rounded-full transition-all duration-200 inline-flex items-center gap-2 border border-white/20">
                        <span class="material-symbols-outlined text-[18px]">near_me</span>
                        <span>Petunjuk Arah & Kontak</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-customer-layout>
