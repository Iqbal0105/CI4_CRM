<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaforaCRM - Aplikasi Pengelola Pelanggan & Beli Ulang via WhatsApp</title>
    <meta name="description"
        content="Kumpulkan data pembeli dari Toko Fisik, Shopee, dan Tokopedia jadi satu. Ingatkan pelanggan belanja lagi secara otomatis lewat WhatsApp tanpa ribet.">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rose: {
                            50: '#fff1f2',
                            100: '#ffe4e6',
                            200: '#fecdd3',
                            300: '#fda4af',
                            400: '#fb7185',
                            500: '#f43f5e',
                            600: '#e11d48',
                            700: '#be123c',
                            800: '#9f1239',
                            900: '#881337',
                        }
                    },
                    fontFamily: {
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'floating': '0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3)',
                    }
                }
            }
        };
    </script>
    <style>
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        @keyframes floatDelayed {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(8px); }
        }
        .animate-float-slow {
            animation: floatSlow 4s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: floatDelayed 4.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white text-slate-800 font-sans antialiased selection:bg-rose-600 selection:text-white">

    <!-- ================================================================= -->
    <!-- 1. STICKY NAVBAR                                                  -->
    <!-- ================================================================= -->
    <header id="main-header"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-200 bg-white/95 backdrop-blur-md border-b border-slate-200/80 py-3 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">

                <!-- Grup Kiri: Logo CRM + Menu Navigasi -->
                <div class="flex items-center gap-7 lg:gap-10">
                    <!-- Brand Logo -->
                    <a href="#hero" class="flex items-center gap-2.5 group shrink-0">
                        <div
                            class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-red-600 to-rose-600 flex items-center justify-center text-white shadow-md shadow-red-600/25 group-hover:scale-105 transition-transform">
                            <!-- Shopping Cart SaaS Icon -->
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-[#0F172A] font-heading">
                            Metafora<span class="text-rose-600">CRM</span>
                        </span>
                    </a>

                    <!-- Navigasi Menu Desktop -->
                    <nav class="hidden md:flex items-center gap-1.5 lg:gap-2">
                        <a href="#overview"
                            class="nav-link px-3 py-1.5 rounded-lg text-sm font-semibold text-slate-700 hover:text-rose-600 transition-colors">Tentang</a>
                        <a href="#features"
                            class="nav-link px-3 py-1.5 rounded-lg text-sm font-semibold text-slate-700 hover:text-rose-600 transition-colors">Fitur</a>
                        <a href="#pricing"
                            class="nav-link px-3 py-1.5 rounded-lg text-sm font-semibold text-slate-700 hover:text-rose-600 transition-colors">Harga</a>
                        <a href="#testimonials"
                            class="nav-link px-3 py-1.5 rounded-lg text-sm font-semibold text-slate-700 hover:text-rose-600 transition-colors">Testimoni</a>
                        <a href="#faq"
                            class="nav-link px-3 py-1.5 rounded-lg text-sm font-semibold text-slate-700 hover:text-rose-600 transition-colors">FAQ</a>
                    </nav>
                </div>

                <!-- Aksi Kanan: Tombol Login & Register -->
                <div class="hidden md:flex items-center gap-3">
                    <?php if (session()->get('is_logged_in')): ?>
                        <a href="<?= base_url('dashboard') ?>"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-bold text-sm text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 shadow-md shadow-red-600/20 transition-all hover:scale-[1.02] active:scale-[0.98] font-heading">
                            <span>Buka Dashboard</span>
                            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full"><?= esc(session()->get('user')['username']) ?></span>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>"
                            class="px-4 py-2 rounded-full font-semibold text-sm text-slate-700 hover:text-rose-600 border border-slate-200 hover:border-rose-300 transition-all">
                            Masuk
                        </a>
                        <a href="<?= base_url('register') ?>"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-bold text-sm text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 shadow-md shadow-red-600/20 transition-all hover:scale-[1.02] active:scale-[0.98] font-heading">
                            Coba Gratis Sekarang
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Tombol Mobile Menu Hamburger -->
                <button id="mobile-menu-btn"
                    class="md:hidden p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition-colors"
                    aria-label="Buka Menu">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-menu"
            class="hidden md:hidden px-4 pt-3 pb-5 border-t border-slate-100 bg-white shadow-lg space-y-2">
            <a href="#overview"
                class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 mobile-item">Tentang</a>
            <a href="#features"
                class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 mobile-item">Fitur</a>
            <a href="#pricing"
                class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 mobile-item">Harga</a>
            <a href="#testimonials"
                class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 mobile-item">Testimoni</a>
            <a href="#faq"
                class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-600 mobile-item">FAQ</a>
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <?php if (session()->get('is_logged_in')): ?>
                    <a href="<?= base_url('dashboard') ?>"
                        class="w-full text-center py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-red-600 to-rose-600 block shadow-md font-heading">
                        Buka Dashboard (<?= esc(session()->get('user')['username']) ?>) →
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>"
                        class="w-full text-center py-2.5 rounded-xl font-bold text-sm text-slate-800 bg-slate-100 block font-heading">
                        Masuk ke Akun
                    </a>
                    <a href="<?= base_url('register') ?>"
                        class="w-full text-center py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-red-600 to-rose-600 block shadow-md font-heading">
                        Daftar Gratis Sekarang →
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- ================================================================= -->
    <!-- 2. HERO SECTION                                                   -->
    <!-- ================================================================= -->
    <section id="hero"
        class="relative pt-24 sm:pt-[96px] pb-16 sm:pb-20 bg-gradient-to-b from-[#070C1B] via-[#0B132B] to-[#0B132B] text-white rounded-b-[40px] sm:rounded-b-[64px] shadow-2xl overflow-hidden">

        <!-- Ambient Radial Glow Effects -->
        <div
            class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-rose-600/15 blur-3xl pointer-events-none">
        </div>
        <div class="absolute bottom-10 left-10 w-80 h-80 rounded-full bg-red-600/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                <!-- Kolom Kiri: Copywriting Headline & Tombol CTA -->
                <div class="lg:col-span-6 text-center lg:text-left">

                    <!-- Pill Tagline Kategori -->
                    <div
                        class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 shadow-xs mb-5">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        <span class="text-xs font-bold tracking-wider text-rose-300 uppercase font-heading">
                            APLIKASI CRM &amp; PENGINGAT REPEAT ORDER OTOMATIS
                        </span>
                    </div>

                    <!-- Headline Kuat -->
                    <h1
                        class="text-3xl sm:text-4xl lg:text-[44px] xl:text-5xl font-extrabold text-white tracking-tight leading-[1.18] sm:leading-[1.15] mb-5 font-heading">
                        Cara Termudah Bikin Pembeli Balik Belanja Lagi di <span class="text-rose-400">Toko Anda.</span>
                    </h1>

                    <!-- Sub-headline -->
                    <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-7 max-w-xl mx-auto lg:mx-0">
                        Kumpulkan data pembeli dari Kasir Toko, Shopee, dan Tokopedia ke dalam satu tempat. Kirim pesan
                        WhatsApp otomatis saat produk pelanggan diperkirakan habis, agar mereka langsung belanja lagi.
                    </p>

                    <!-- Tombol Aksi CTA Ganda -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 mb-6">
                        <!-- Tombol Utama: Daftar / Masuk -->
                        <a href="<?= base_url('register') ?>"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3 rounded-full font-extrabold text-white bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 shadow-lg shadow-rose-900/40 transition-all hover:-translate-y-0.5 active:translate-y-0 text-base font-heading">
                            Mulai Gratis Sekarang
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>

                        <!-- Tombol Kedua: Konsultasi WhatsApp -->
                        <a href="https://wa.me/6289664230232?text=Halo%20Admin%20MetaforaCRM%2C%20saya%20ingin%20konsultasi%20fitur%20CRM%20WhatsApp%20dan%20Kasir%20POS."
                            target="_blank" rel="noopener noreferrer"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-xs transition-all text-base">
                            <svg class="w-4 h-4 text-emerald-400 fill-current" viewBox="0 0 448 512">
                                <path
                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                            </svg>
                            <span>Tanya Tim Kami via WA</span>
                        </a>
                    </div>

                    <!-- Bukti Kepercayaan Rating Bintang 5 -->
                    <div class="flex items-center justify-center lg:justify-start gap-3">
                        <div class="flex text-amber-400 text-sm">★★★★★</div>
                        <span class="text-xs text-slate-300">
                            Dipercaya <strong class="text-white font-bold">200+ Pemilik Toko &amp; Brand</strong> di
                            Indonesia
                        </span>
                    </div>

                </div>

                <!-- Kolom Kanan: Visual Mockup Showcase -->
                <div class="lg:col-span-6 flex items-center justify-center">
                    <div class="relative w-full max-w-[480px] sm:max-w-[500px] py-4 select-none">

                        <!-- Visual Container Utama -->
                        <div
                            class="relative bg-[#0F172A]/90 backdrop-blur-md rounded-3xl p-6 border border-white/15 shadow-2xl">

                            <!-- Header Mini Bar Mockup -->
                            <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-white/10">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                                    <span class="text-xs font-bold text-white/90 ml-2 font-heading tracking-wide">DATA
                                        PENJUALAN TERKUMPUL</span>
                                </div>
                                <span
                                    class="px-2.5 py-0.5 rounded-full bg-rose-500/20 border border-rose-500/40 text-[10px] font-bold text-rose-300">
                                    ● Aktif Otomatis
                                </span>
                            </div>

                            <!-- Showcase Konten: Profil Pelanggan & Alur Otomasi -->
                            <div class="space-y-3.5">
                                <!-- Pelanggan Card -->
                                <div
                                    class="bg-white rounded-2xl p-4 text-slate-900 shadow-xl border border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-full overflow-hidden shrink-0 bg-rose-100 text-rose-600 font-bold flex items-center justify-center border-2 border-rose-500 ring-2 ring-rose-500/30">
                                            AP
                                        </div>
                                        <div>
                                            <div class="text-sm font-extrabold text-slate-900 font-heading">Aditya
                                                Pratama</div>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span
                                                    class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-700 text-[10px] font-bold">Shopee
                                                    &amp; Kasir Toko</span>
                                                <span class="text-[11px] text-slate-500 font-medium">• Surabaya</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-slate-500 font-medium">Total Belanja</div>
                                        <div class="text-sm font-extrabold text-rose-600 font-heading">Rp 4.850.000
                                        </div>
                                    </div>
                                </div>

                                <!-- Pesan Otomasi WhatsApp -->
                                <div
                                    class="bg-white rounded-2xl p-3.5 text-slate-800 shadow-xl border border-slate-100">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <div
                                            class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">
                                            ✓
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-800">Pengingat WhatsApp Otomatis
                                            (Tiap 30 Hari)</span>
                                    </div>
                                    <div
                                        class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 text-xs text-slate-600 leading-relaxed">
                                        "Halo Kak Aditya! Serum wajah yang dibeli sebulan lalu mungkin sudah mau habis
                                        nih. Khusus hari ini kami siapkan <span class="font-bold text-rose-600">Diskon
                                            15%</span> untuk pemesanan ulang ya 🎁"
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FLOATING CARD 1: Sumber Pembeli (Kanan Atas) -->
                        <div
                            class="absolute -top-4 -right-3 sm:-right-5 bg-[#0F172A] p-3 rounded-2xl shadow-floating border border-white/15 text-white animate-float-slow hidden sm:flex items-center gap-3 z-20">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#F43F5E" stroke-width="6"
                                        stroke-dasharray="45 55" stroke-dashoffset="25"></circle>
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#10B981" stroke-width="6"
                                        stroke-dasharray="30 70" stroke-dashoffset="80"></circle>
                                    <circle cx="18" cy="18" r="14" fill="none" stroke="#2D6A4F" stroke-width="6"
                                        stroke-dasharray="25 75" stroke-dashoffset="50"></circle>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sumber
                                    Pembeli</div>
                                <div class="text-xs font-extrabold text-white font-heading">Shopee &amp; Kasir Jadi Satu
                                </div>
                            </div>
                        </div>

                        <!-- FLOATING CARD 2: Repeat Order Closing (Kiri Bawah) -->
                        <div
                            class="absolute -bottom-4 -left-3 sm:-left-5 bg-[#0F172A] p-3 sm:p-3.5 rounded-2xl shadow-floating border border-white/15 text-white animate-float-delayed flex items-center gap-3 z-20">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pembelian
                                    Ulang</div>
                                <div class="text-xs font-extrabold text-rose-400 font-heading">Pembeli Beli Lagi Naik 3x
                                    Lipat</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 3. FLOATING METRIC STRIP                                          -->
    <!-- ================================================================= -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 -mt-12 sm:-mt-16 relative z-30">
        <div
            class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100 text-center">

            <!-- Metric 1: Users / Pelanggan -->
            <div class="flex items-center justify-center gap-4 pt-2 md:pt-0">
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] font-heading">50.000+</div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-500">Kontak Pembeli Tercatat Rapi</div>
                </div>
            </div>

            <!-- Metric 2: Locations / Brand & Ritel -->
            <div class="flex items-center justify-center gap-4 pt-4 md:pt-0 md:pl-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] font-heading">200+</div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-500">Toko &amp; Brand Menggunakan</div>
                </div>
            </div>

            <!-- Metric 3: Servers / Kenaikan Repeat Order -->
            <div class="flex items-center justify-center gap-4 pt-4 md:pt-0 md:pl-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                        <line x1="6" y1="6" x2="6.01" y2="6"></line>
                        <line x1="6" y1="18" x2="6.01" y2="18"></line>
                    </svg>
                </div>
                <div class="text-left">
                    <div class="text-2xl sm:text-3xl font-extrabold text-rose-600 font-heading">+310%</div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-500">Pembeli Belanja Lagi</div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================================================================= -->
    <!-- 4. OVERVIEW SECTION                                               -->
    <!-- ================================================================= -->
    <section id="overview" class="pt-12 sm:pt-14 pb-8 sm:pb-10 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">

                <!-- Teks Pengantar -->
                <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                    <span class="text-xs font-bold uppercase tracking-widest text-rose-600 font-heading">
                        SATUKAN SEMUA PENJUALAN
                    </span>
                    <h2
                        class="text-2xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight leading-tight font-heading">
                        Tak Perlu Buka Banyak Aplikasi.<br>
                        <span class="text-rose-600">Semua Data Pembeli Rapi di Satu Tempat.</span>
                    </h2>
                    <p class="text-base text-slate-600 leading-relaxed">
                        Mulai dari riwayat belanja pembeli, perkiraan kapan barang mereka habis, sampai kirim pesan
                        sapaan otomatis. Semua nomor WhatsApp pembeli tersimpan rapi milik toko Anda sendiri, jadi tidak
                        takut hilang saat ganti admin atau ganti kasir.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 text-left">
                        <div
                            class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/70 shadow-xs hover:border-rose-300 hover:bg-rose-50/20 transition-all">
                            <div
                                class="w-8 h-8 rounded-xl bg-orange-100 text-[#EA580C] flex items-center justify-center font-bold text-sm mb-2">
                                🏬
                            </div>
                            <div class="font-bold text-slate-800 text-sm">Kasir Toko &amp; Marketplace</div>
                            <div class="text-xs text-slate-500 mt-1">Data pembeli dari kasir toko, Shopee, dan Tokopedia langsung terhubung ke satu nomor WhatsApp yang sama.</div>
                        </div>

                        <div
                            class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/70 shadow-xs hover:border-rose-300 hover:bg-rose-50/20 transition-all">
                            <div
                                class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-sm mb-2">
                                💬
                            </div>
                            <div class="font-bold text-slate-800 text-sm">Sapaan WA Otomatis &amp; Personal</div>
                            <div class="text-xs text-slate-500 mt-1">Kirim pesan WhatsApp pengingat secara otomatis dengan menyebut nama pembeli satu per satu tanpa repot ketik manual.</div>
                        </div>
                    </div>
                </div>

                <!-- Showcase Ilustrasi Keranjang Pintar -->
                <div class="lg:col-span-6 flex items-center justify-center">
                    <div
                        class="relative w-full max-w-md bg-gradient-to-tr from-[#070C1B] via-[#0B132B] to-[#131D36] border border-slate-800 rounded-3xl p-8 text-white shadow-2xl">
                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-rose-500 rounded-full blur-2xl opacity-20">
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center font-black">
                                    🛒
                                </div>
                                <span class="font-extrabold text-lg tracking-tight font-heading">Catatan Pembeli Pintar</span>
                            </div>
                            <span
                                class="px-2.5 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-bold">Bebas Dobel</span>
                        </div>

                        <div class="space-y-3">
                            <div
                                class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl flex items-center justify-between border border-white/10">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-orange-400"></span>
                                    <span class="text-xs font-bold">Belanja di Shopee #SHP-9921</span>
                                </div>
                                <span class="text-xs font-bold text-emerald-400">Tersimpan</span>
                            </div>

                            <div
                                class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl flex items-center justify-between border border-white/10">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                                    <span class="text-xs font-bold">Belanja di Kasir Toko #POS-1044</span>
                                </div>
                                <span class="text-xs font-bold text-emerald-400">Tersimpan</span>
                            </div>

                            <div
                                class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl flex items-center justify-between border border-rose-500/30 bg-rose-950/20">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-pulse"></span>
                                    <span class="text-xs font-bold text-rose-200">Pesan WA Pengingat Belanja</span>
                                </div>
                                <span class="text-xs font-bold text-rose-400">Siap Terkirim</span>
                            </div>
                        </div>

                        <div
                            class="mt-6 pt-4 border-t border-white/15 flex items-center justify-between text-xs text-slate-300">
                            <span>Jadwal Beli Lagi: 30 Hari Sekali</span>
                            <span class="font-bold text-rose-400">Peluang Beli Ulang: Sangat Tinggi</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 5. FITUR UTAMA                                                    -->
    <!-- ================================================================= -->
    <section id="features" class="pt-12 sm:pt-14 pb-12 sm:pb-14 bg-[#0B132B] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <!-- Section Header -->
            <span class="text-xs font-bold uppercase tracking-widest text-rose-400 font-heading">
                FITUR PRAKTIS &amp; OTOMATIS
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mt-2 mb-3 font-heading text-white">
                Cara Mudah Bikin Pelanggan Belanja Terus
            </h2>
            <p class="text-slate-300 max-w-2xl mx-auto mb-10 sm:mb-12 text-base sm:text-lg">
                Dibuat khusus untuk Anda yang punya toko fisik atau jualan online agar tidak repot kirim pesan pengingat
                satu per satu secara manual.
            </p>

            <!-- 3 Kolom Kartu Fitur -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Kartu 1: Ingatkan Pembeli -->
                <div
                    class="bg-white rounded-3xl p-7 text-slate-800 shadow-xl flex flex-col justify-between hover:-translate-y-1.5 transition-transform duration-300">
                    <div>
                        <!-- Header Banner Ilustrasi Simpel -->
                        <div
                            class="w-full h-52 rounded-2xl mb-6 relative overflow-hidden bg-gradient-to-br from-rose-50/80 via-red-50/50 to-slate-50/70 border border-rose-100 p-4 flex flex-col justify-center items-center shadow-inner group">
                            <!-- Mini WA Reminder Card -->
                            <div
                                class="w-full max-w-[280px] bg-white rounded-2xl p-3.5 shadow-md border border-slate-100/80 transform group-hover:scale-[1.02] transition-transform duration-300 text-left">
                                <div class="flex items-center justify-between mb-2 pb-1.5 border-b border-slate-50">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-[#25D366] flex items-center justify-center text-white shadow-xs">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 448 512">
                                                <path
                                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-[11px] font-bold text-slate-800 leading-tight">Pengingat WhatsApp</div>
                                            <div class="text-[9px] text-emerald-600 font-medium">● Otomatis H-3</div>
                                        </div>
                                    </div>
                                    <span class="text-[9px] text-slate-400">09.41</span>
                                </div>
                                <div class="bg-rose-50 border border-rose-100 rounded-xl p-2 text-left mb-2">
                                    <p class="text-[10px] text-slate-700 leading-snug">
                                        "Halo Kak Sarah! Sabun wajah Anda kira-kira mau habis nih. Mau diisi ulang sekarang? Ada diskon 10% lho 😊"
                                    </p>
                                </div>
                                <div class="flex items-center gap-1.5 justify-end">
                                    <span
                                        class="px-2 py-0.5 rounded-md bg-gradient-to-r from-red-600 to-rose-600 text-[9px] font-bold text-white shadow-xs">Pesan Ulang →</span>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#0F172A] font-heading mb-3 text-left">
                            Ingatkan Pembeli di Waktu yang Pas
                        </h3>
                        <p class="text-sm text-slate-600 leading-relaxed text-left">
                            Sistem otomatis menghitung kapan barang yang dibeli pelanggan biasanya habis. Begitu
                            waktunya tiba, pesan WhatsApp otomatis terkirim menawarkan promo isi ulang.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100 text-left">
                        <a href="<?= base_url('register') ?>" class="text-xs font-bold text-rose-600 inline-flex items-center gap-1 hover:underline">
                            Coba Fitur Ini Sekarang →
                        </a>
                    </div>
                </div>

                <!-- Kartu 2: Pantau Penjualan -->
                <div
                    class="bg-white rounded-3xl p-7 text-slate-800 shadow-xl flex flex-col justify-between hover:-translate-y-1.5 transition-transform duration-300">
                    <div>
                        <!-- Header Banner Ilustrasi Simpel -->
                        <div
                            class="w-full h-52 rounded-2xl mb-6 relative overflow-hidden bg-gradient-to-br from-rose-50/80 via-red-50/50 to-slate-50/70 border border-rose-100 p-4 flex flex-col justify-center items-center shadow-inner group">
                            <!-- Mini Sales Analytics Card -->
                            <div
                                class="w-full max-w-[280px] bg-white rounded-2xl p-3.5 shadow-md border border-slate-100/80 transform group-hover:scale-[1.02] transition-transform duration-300 text-left">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Omzet Toko Bulan Ini</span>
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-[#ECFDF5] text-[#10B981] text-[10px] font-extrabold">▲ +28%</span>
                                </div>
                                <div class="text-lg font-black text-slate-900 font-heading mb-2">
                                    Rp 128.450.000
                                </div>
                                <!-- Bar Chart -->
                                <div class="flex items-end justify-between gap-2 h-14 pt-2 border-t border-slate-100">
                                    <div class="flex-1 flex flex-col items-center gap-1">
                                        <div class="w-full bg-rose-200 rounded-t-md h-5"></div>
                                        <span class="text-[9px] text-slate-400">Mg 1</span>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-1">
                                        <div class="w-full bg-rose-300 rounded-t-md h-7"></div>
                                        <span class="text-[9px] text-slate-400">Mg 2</span>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-1">
                                        <div class="w-full bg-rose-400 rounded-t-md h-10"></div>
                                        <span class="text-[9px] text-slate-400">Mg 3</span>
                                    </div>
                                    <div class="flex-1 flex flex-col items-center gap-1">
                                        <div
                                            class="w-full bg-gradient-to-t from-red-600 to-rose-600 rounded-t-md h-14 shadow-xs">
                                        </div>
                                        <span class="text-[9px] font-bold text-rose-600">Mg 4</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#0F172A] font-heading mb-3 text-left">
                            Pantau Penjualan Tanpa Ribet
                        </h3>
                        <p class="text-sm text-slate-600 leading-relaxed text-left">
                            Lihat berapa total omzet dari Shopee, Tokopedia, dan kasir toko Anda dalam satu layar yang
                            gampang dipahami. Anda langsung tahu produk apa yang paling laris dibeli lagi.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100 text-left">
                        <a href="<?= base_url('register') ?>" class="text-xs font-bold text-rose-600 inline-flex items-center gap-1 hover:underline">
                            Lihat Contoh Laporan →
                        </a>
                    </div>
                </div>

                <!-- Kartu 3: Balas dari HP -->
                <div
                    class="bg-white rounded-3xl p-7 text-slate-800 shadow-xl flex flex-col justify-between hover:-translate-y-1.5 transition-transform duration-300">
                    <div>
                        <!-- Header Banner Ilustrasi Simpel -->
                        <div
                            class="w-full h-52 rounded-2xl mb-6 relative overflow-hidden bg-gradient-to-br from-amber-50/80 via-orange-50/50 to-rose-50/70 border border-orange-100 p-4 flex flex-col justify-center items-center shadow-inner group">
                            <!-- Mini Mobile CRM Chat Card -->
                            <div
                                class="w-full max-w-[280px] bg-white rounded-2xl p-3.5 shadow-md border border-slate-100/80 transform group-hover:scale-[1.02] transition-transform duration-300 text-left">
                                <div class="flex items-center justify-between pb-1.5 mb-2 border-b border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 font-bold text-[10px] flex items-center justify-center">
                                            📱
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-800">Chat WhatsApp dari HP</span>
                                    </div>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                </div>
                                <div class="space-y-1.5 text-left mb-2">
                                    <div class="bg-slate-100 rounded-lg rounded-tl-none p-1.5 max-w-[85%]">
                                        <p class="text-[10px] text-slate-700 leading-tight">"Kak, pesanan kopi arabika saya sudah dikirim?"</p>
                                    </div>
                                    <div
                                        class="bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-lg rounded-tr-none p-1.5 max-w-[85%] ml-auto text-right">
                                        <p class="text-[10px] leading-tight">"Sudah kak, resi kurir otomatis masuk ke WA ya! 👍"</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-1 text-[9px] text-slate-400">
                                    <span>✓ Buka dari browser HP</span>
                                    <span class="text-rose-600 font-semibold">Respon Cepat</span>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#0F172A] font-heading mb-3 text-left">
                            Pantau &amp; Balas dari HP Kapan Saja
                        </h3>
                        <p class="text-sm text-slate-600 leading-relaxed text-left">
                            Mau dari rumah, di perjalanan, atau lagi cek toko cabang, Anda dan tim tetap bisa melihat
                            daftar pembeli dan membalas pesan WhatsApp pelanggan langsung dari smartphone tanpa perlu
                            buka laptop.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-100 text-left">
                        <a href="<?= base_url('register') ?>" class="text-xs font-bold text-rose-600 inline-flex items-center gap-1 hover:underline">
                            Lihat Tampilan Mobile →
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 6. PRICING & MATRIX SECTION                                       -->
    <!-- ================================================================= -->
    <section id="pricing" class="pt-10 sm:pt-12 pb-12 sm:pb-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <!-- Section Header -->
            <span class="text-xs font-bold uppercase tracking-widest text-rose-600 font-heading">
                INVESTASI TRANSPARAN &amp; TERJANGKAU
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight mt-2 mb-3 font-heading">
                Pilih Paket yang Tepat untuk Skala Bisnis Anda
            </h2>
            <p class="text-slate-600 max-w-xl mx-auto mb-8 text-base">
                Tanpa biaya tambahan tersembunyi. Tidak ada kontrak yang mengikat. Anda bebas berhenti atau ganti paket kapan saja.
            </p>

            <!-- 3 Kartu Paket Harga -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch mb-10 sm:mb-12">

                <!-- Paket 1: Basic -->
                <div
                    class="group rounded-3xl p-8 bg-slate-50 border border-slate-200/90 relative flex flex-col justify-between text-left shadow-sm hover:shadow-xl transition-all duration-300">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider font-heading">BASIC</div>
                        <h3 class="text-xl font-extrabold text-[#0F172A] font-heading mt-1 mb-4">Toko Tunggal &amp; Perintis</h3>
                        <div class="mb-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] font-heading">Rp 249.000</span>
                                <span class="text-xs sm:text-sm text-slate-500 font-medium shrink-0">/bulan</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium mt-1">Ditagih bulanan</div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed mb-6">
                            Solusi tepat untuk pemilik 1 toko fisik atau online yang ingin mulai merapikan database pelanggan dan riwayat transaksi di satu tempat.
                        </p>
                        <ul class="space-y-3 text-xs text-slate-700">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Kapasitas hingga 2.000 Kontak Pelanggan
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Sinkronisasi Data dari 1 Kanal Penjualan
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Dashboard Ringkasan &amp; Laporan Penjualan
                            </li>
                            <li class="flex items-center gap-2 text-slate-400">
                                <span>—</span> Otomatisasi Pesan Pengingat WhatsApp
                            </li>
                        </ul>
                    </div>
                    <div class="pt-8 mt-6">
                        <a href="<?= base_url('register?plan=basic') ?>"
                            class="w-full text-center py-3 rounded-full font-bold text-xs text-slate-800 bg-white hover:bg-rose-50 hover:text-rose-600 hover:border-rose-300 border border-slate-300 block transition-all shadow-xs group-hover:shadow-md group-hover:scale-[1.02]">
                            Pilih Paket Basic
                        </a>
                    </div>
                </div>

                <!-- Paket 2: Pro (Featured) -->
                <div
                    class="group rounded-3xl p-8 bg-gradient-to-b from-rose-50/70 via-white to-white border-2 border-rose-600 shadow-xl relative flex flex-col justify-between text-left transition-all duration-300">
                    <div
                        class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-red-600 to-rose-600 text-white text-[11px] font-black uppercase tracking-wider font-heading shadow-md">
                        PALING BANYAK DIPILIH
                    </div>
                    <div>
                        <div class="text-xs font-bold text-rose-600 uppercase tracking-wider font-heading mt-1">PRO</div>
                        <h3 class="text-xl font-extrabold text-[#0F172A] font-heading mt-1 mb-4">Multi-Cabang &amp; Omnichannel</h3>
                        <div class="mb-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl font-extrabold text-rose-600 font-heading">Rp 599.000</span>
                                <span class="text-xs sm:text-sm text-slate-500 font-medium shrink-0">/bulan</span>
                            </div>
                            <div class="text-[11px] text-rose-600 font-semibold mt-1">Ditagih bulanan</div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed mb-6">
                            Pilihan terbaik untuk bisnis aktif di Shopee, Tokopedia, dan kasir toko fisik yang ingin meningkatkan repeat order secara otomatis.
                        </p>
                        <ul class="space-y-3 text-xs text-slate-700">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Kapasitas hingga 25.000 Kontak Pelanggan
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Konsolidasi Shopee, Tokopedia, &amp; Kasir Toko
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Otomatisasi Follow-Up WhatsApp
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Prediksi Siklus Produk Habis
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Segmentasi Pelanggan RFM Otomatis
                            </li>
                        </ul>
                    </div>
                    <div class="pt-8 mt-6">
                        <a href="<?= base_url('register?plan=pro') ?>"
                            class="w-full text-center py-3.5 rounded-full font-bold text-xs text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 block transition-all shadow-md shadow-red-600/25 group-hover:shadow-xl group-hover:shadow-red-600/35 group-hover:scale-[1.02]">
                            Coba Paket Pro Gratis 14 Hari →
                        </a>
                    </div>
                </div>

                <!-- Paket 3: Max (Enterprise) -->
                <div
                    class="group rounded-3xl p-8 bg-slate-50 border border-slate-200/90 relative flex flex-col justify-between text-left shadow-sm hover:shadow-xl transition-all duration-300">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider font-heading">MAX</div>
                        <h3 class="text-xl font-extrabold text-[#0F172A] font-heading mt-1 mb-4">Skala Besar &amp; Multi-Cabang</h3>
                        <div class="mb-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] font-heading">Rp 1.499.000</span>
                                <span class="text-xs sm:text-sm text-slate-500 font-medium shrink-0">/bulan</span>
                            </div>
                            <div class="text-[11px] text-slate-500 font-medium mt-1">Ditagih bulanan</div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed mb-6">
                            Dirancang untuk jaringan ritel besar dengan puluhan cabang, volume pesanan tinggi, dan kebutuhan pendampingan khusus.
                        </p>
                        <ul class="space-y-3 text-xs text-slate-700">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Kontak Pelanggan Unlimited
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Akses Multi-Cabang &amp; Multi-Outlet
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Jalur Prioritas WhatsApp Berkecepatan Tinggi
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Integrasi Kustom (API) ke ERP &amp; Inventori
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Manajer Akun Khusus 24/7
                            </li>
                        </ul>
                    </div>
                    <div class="pt-8 mt-6">
                        <a href="https://wa.me/6289664230232?text=Halo%2C%20saya%20ingin%20konsultasi%20paket%20MAX%20MetaforaCRM%20untuk%20skala%20besar."
                            target="_blank"
                            class="w-full text-center py-3 rounded-full font-bold text-xs text-slate-800 bg-white hover:bg-rose-50 hover:text-rose-600 hover:border-rose-300 border border-slate-300 block transition-all shadow-xs group-hover:border-rose-300 group-hover:text-rose-600 group-hover:shadow-md group-hover:scale-[1.02]">
                            Hubungi Tim Sales
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 7. TESTIMONIALS SECTION                                           -->
    <!-- ================================================================= -->
    <section id="testimonials" class="pt-8 sm:pt-10 pb-10 sm:pb-12 bg-white border-t border-slate-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <span class="text-xs font-bold uppercase tracking-widest text-rose-600 font-heading">
                CERITA DARI PEMILIK TOKO
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight mt-2 mb-3 font-heading">
                Apa Kata Mereka yang Sudah Pakai?
            </h2>
            <p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto mb-8 sm:mb-10">
                Kisah nyata dari para pemilik toko retail, fashion, kosmetik, dan jualan online yang berhasil menaikkan
                pembelian ulang.
            </p>

            <!-- 3 Kolom Review Pelanggan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left items-stretch">

                <!-- Review 1 -->
                <div
                    class="bg-white rounded-3xl p-6 sm:p-7 shadow-lg shadow-slate-100/80 border border-slate-200/80 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-xl hover:border-rose-300 transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex text-amber-400 text-sm">★★★★★</div>
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">
                                Pengguna Aktif
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed mb-6 font-normal">
                            "Dulu pusing mencatat nomor WhatsApp pembeli karena data dari kasir toko fisik dan toko
                            Shopee terpisah-pisah. Sekarang semua kontak otomatis rapi. Pesan pengingat otomatisnya
                            bikin pelanggan rajin balik belanja sampai 3 kali lipat!"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <div
                            class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 font-extrabold flex items-center justify-center text-sm font-heading shrink-0 shadow-xs">
                            HK
                        </div>
                        <div>
                            <div class="text-sm font-extrabold text-[#0F172A] font-heading leading-tight">Hendra Kusuma</div>
                            <div class="text-[11px] text-slate-500 font-medium mt-0.5">Toko GlowSkin Retail • Surabaya</div>
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div
                    class="bg-white rounded-3xl p-6 sm:p-7 shadow-lg shadow-slate-100/80 border border-slate-200/80 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-xl hover:border-rose-300 transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex text-amber-400 text-sm">★★★★★</div>
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">
                                Pengguna Aktif
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed mb-6 font-normal">
                            "Tiap ada pergantian kasir atau admin toko, biasanya nomor WhatsApp pembeli ikut tercecer di
                            HP lama. Sejak pakai sistem ini, data pembeli 100% aman tersimpan rapi milik toko. Upload
                            laporan Excel penjualan bulanan juga praktis cuma 1 klik."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <div
                            class="w-10 h-10 rounded-full bg-[#0B132B] text-white font-extrabold flex items-center justify-center text-sm font-heading shrink-0 shadow-xs">
                            SR
                        </div>
                        <div>
                            <div class="text-sm font-extrabold text-[#0F172A] font-heading leading-tight">Siti Rahmawati</div>
                            <div class="text-[11px] text-slate-500 font-medium mt-0.5">Busana Al-Madina • Bandung</div>
                        </div>
                    </div>
                </div>

                <!-- Review 3 -->
                <div
                    class="bg-white rounded-3xl p-6 sm:p-7 shadow-lg shadow-slate-100/80 border border-slate-200/80 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-xl hover:border-rose-300 transition-all duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex text-amber-400 text-sm">★★★★★</div>
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">
                                Pengguna Aktif
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed mb-6 font-normal">
                            "Fitur prediksi kapan produk pelanggan habis sangat manjur. Pas stok kopi langganan diperkirakan
                            tinggal 2 hari, WhatsApp pengingat otomatis terkirim dengan ramah. Pelanggan merasa senang
                            dan sangat diperhatikan, jualan jadi lancar terus."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-900 text-white font-extrabold flex items-center justify-center text-sm font-heading shrink-0 shadow-xs">
                            BS
                        </div>
                        <div>
                            <div class="text-sm font-extrabold text-[#0F172A] font-heading leading-tight">Budi Santoso</div>
                            <div class="text-[11px] text-slate-500 font-medium mt-0.5">Kopi Roastery Nusantara • Jakarta</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 8. FAQ SECTION                                                    -->
    <!-- ================================================================= -->
    <section id="faq" class="pt-8 sm:pt-10 pb-10 sm:pb-12 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-10">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-600 font-heading">
                    PERTANYAAN UMUM (FAQ)
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight mt-2 mb-3 font-heading">
                    Pertanyaan yang Sering Ditanyakan
                </h2>
                <p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto">
                    Jawaban praktis dan jelas seputar cara kerja pengingat WhatsApp, impor data marketplace, dan keamanan nomor kontak pembeli Anda.
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="border border-slate-200 rounded-2xl p-5 hover:border-rose-300 transition-colors bg-slate-50/50">
                    <button type="button" class="w-full flex items-center justify-between text-left font-extrabold text-[#0F172A] font-heading text-sm sm:text-base faq-toggle">
                        <span>Bagaimana cara memasukkan data penjualan dari Shopee, Tokopedia, dan kasir toko?</span>
                        <span class="text-xl text-rose-600 ml-3 font-mono transform transition-transform duration-200 faq-icon">+</span>
                    </button>
                    <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed hidden faq-answer border-t border-slate-100 pt-3">
                        Sangat mudah dan praktis! Anda tidak perlu repot menyambungkan akun toko. Cukup download file laporan pesanan (Excel/CSV) dari Seller Center Shopee, Tokopedia, atau TikTok Shop, lalu upload/import ke dalam sistem CRM. Data nomor WhatsApp, nama pembeli, dan barang belanjaan langsung tersimpan rapi secara otomatis. Untuk kasir toko fisik, data transaksi otomatis tercatat langsung dari kasir.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border border-slate-200 rounded-2xl p-5 hover:border-rose-300 transition-colors bg-slate-50/50">
                    <button type="button" class="w-full flex items-center justify-between text-left font-extrabold text-[#0F172A] font-heading text-sm sm:text-base faq-toggle">
                        <span>Apakah nomor WhatsApp toko saya aman dari risiko blokir (banned)?</span>
                        <span class="text-xl text-rose-600 ml-3 font-mono transform transition-transform duration-200 faq-icon">+</span>
                    </button>
                    <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed hidden faq-answer border-t border-slate-100 pt-3">
                        Sangat aman. Sistem CRM menggunakan jalur resmi WhatsApp Business serta mengatur jeda pengiriman pesan secara wajar (tidak membombardir serentak seperti spam). Pesan yang dikirim juga ramah dan personal karena hanya ditujukan ke pelanggan yang memang pernah belanja di toko Anda.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border border-slate-200 rounded-2xl p-5 hover:border-rose-300 transition-colors bg-slate-50/50">
                    <button type="button" class="w-full flex items-center justify-between text-left font-extrabold text-[#0F172A] font-heading text-sm sm:text-base faq-toggle">
                        <span>Bagaimana sistem tahu kapan harus mengirim pesan pengingat belanja ulang?</span>
                        <span class="text-xl text-rose-600 ml-3 font-mono transform transition-transform duration-200 faq-icon">+</span>
                    </button>
                    <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed hidden faq-answer border-t border-slate-100 pt-3">
                        Anda bisa menentukan perkiraan waktu habis produk dengan mudah. Misalnya, jika pelanggan membeli produk perawatan atau kopi yang biasa habis dalam 30 hari, sistem otomatis mengirim pesan sapaan ramah di hari ke-27 untuk mengingatkan stoknya yang hampir habis.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border border-slate-200 rounded-2xl p-5 hover:border-rose-300 transition-colors bg-slate-50/50">
                    <button type="button" class="w-full flex items-center justify-between text-left font-extrabold text-[#0F172A] font-heading text-sm sm:text-base faq-toggle">
                        <span>Saya sudah punya ribuan kontak pelanggan di Excel atau HP, apakah bisa dipindahkan ke sini?</span>
                        <span class="text-xl text-rose-600 ml-3 font-mono transform transition-transform duration-200 faq-icon">+</span>
                    </button>
                    <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed hidden faq-answer border-t border-slate-100 pt-3">
                        Tentu saja bisa. Anda cukup mengunggah file Excel atau CSV kontak yang sudah ada hanya dalam 1 kali klik. Sistem juga otomatis merapikan nomor yang ganda (dobel) agar pesan tidak terkirim dua kali ke orang yang sama.
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="border border-slate-200 rounded-2xl p-5 hover:border-rose-300 transition-colors bg-slate-50/50">
                    <button type="button" class="w-full flex items-center justify-between text-left font-extrabold text-[#0F172A] font-heading text-sm sm:text-base faq-toggle">
                        <span>Apakah aplikasi ini cocok untuk toko kecil atau yang baru punya 1 toko online?</span>
                        <span class="text-xl text-rose-600 ml-3 font-mono transform transition-transform duration-200 faq-icon">+</span>
                    </button>
                    <div class="mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed hidden faq-answer border-t border-slate-100 pt-3">
                        Sangat cocok. Mulai dari toko yang baru merintis hingga bisnis dengan banyak cabang bisa menggunakannya. Mengumpulkan kontak pembeli sejak awal justru membuat toko Anda lebih cepat berkembang tanpa harus terus-menerus keluar biaya iklan mahal.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 9. BOTTOM BANNER CTA                                              -->
    <!-- ================================================================= -->
    <section class="py-8 sm:py-10 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="relative bg-gradient-to-r from-[#070C1B] via-[#0B132B] to-[#131D36] rounded-3xl p-8 sm:p-14 text-white text-center shadow-2xl overflow-hidden border border-slate-800">
                <div class="absolute -top-16 -right-16 w-60 h-60 bg-rose-500 rounded-full blur-3xl opacity-30"></div>

                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight mb-4 font-heading">
                        Siap Bikin Pelanggan Belanja Lagi ke Toko Anda?
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8">
                        Mulai rapikan data pembeli dari kasir toko &amp; marketplace Anda hari ini. Nikmati kenaikan omzet dari pelanggan setia tanpa perlu terus-menerus membakar biaya iklan.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
                        <a href="<?= base_url('register') ?>"
                            class="w-full sm:w-auto px-8 py-3.5 rounded-full font-extrabold text-white bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 shadow-lg shadow-rose-900/40 transition-all font-heading text-sm">
                            Mulai Gratis Sekarang →
                        </a>
                        <a href="https://wa.me/6289664230232?text=Halo%20Admin%20MetaforaCRM%2C%20saya%20ingin%20tanya-tanya%20dulu%20soal%20aplikasi%20CRM%20toko."
                            target="_blank"
                            class="w-full sm:w-auto px-7 py-3.5 rounded-full font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all text-sm">
                            Tanya Dulu via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 10. FOOTER LENGKAP                                                -->
    <!-- ================================================================= -->
    <footer class="bg-[#070C1B] text-slate-300 pt-14 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10 border-b border-slate-800">

                <!-- Kolom 1: Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-9 h-9 rounded-xl bg-gradient-to-tr from-red-600 to-rose-600 flex items-center justify-center text-white shadow-md shadow-red-600/30">
                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <span class="font-extrabold text-lg text-white font-heading">
                            Metafora<span class="text-rose-500">CRM</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Aplikasi pintar terintegrasi untuk menghubungkan kasir toko fisik dan marketplace online dengan otomatisasi pengingat repeat order via WhatsApp.
                    </p>
                </div>

                <!-- Kolom 2: Navigasi Halaman -->
                <div>
                    <h4 class="font-extrabold text-white font-heading mb-4 uppercase tracking-wider text-xs">Menu Cepat</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="#hero" class="hover:text-rose-400 transition-colors">Beranda</a></li>
                        <li><a href="#overview" class="hover:text-rose-400 transition-colors">Tentang Sistem</a></li>
                        <li><a href="#features" class="hover:text-rose-400 transition-colors">Fitur Toko</a></li>
                        <li><a href="#pricing" class="hover:text-rose-400 transition-colors">Paket Harga</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Fitur & Modul -->
                <div>
                    <h4 class="font-extrabold text-white font-heading mb-4 uppercase tracking-wider text-xs">Modul Terintegrasi</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="#features" class="hover:text-rose-400 transition-colors">Kasir POS &amp; Toko Fisik</a></li>
                        <li><a href="#features" class="hover:text-rose-400 transition-colors">Penjualan Shopee &amp; Tokopedia</a></li>
                        <li><a href="#features" class="hover:text-rose-400 transition-colors">Otomasi Pengingat WhatsApp</a></li>
                        <li><a href="#features" class="hover:text-rose-400 transition-colors">Analisis RFM &amp; Kohort</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Bantuan & Masuk -->
                <div>
                    <h4 class="font-extrabold text-white font-heading mb-4 uppercase tracking-wider text-xs">Bantuan &amp; Akses</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="https://wa.me/6289664230232" target="_blank" class="hover:text-rose-400 transition-colors">Chat Tim Support WA</a></li>
                        <li><a href="#faq" class="hover:text-rose-400 transition-colors">Tanya Jawab (FAQ)</a></li>
                        <li><a href="<?= base_url('login') ?>" class="hover:text-rose-400 transition-colors font-semibold">Masuk ke Portal Login</a></li>
                        <li><a href="<?= base_url('register') ?>" class="text-rose-400 hover:text-rose-300 transition-colors font-bold">Daftar Akun Baru</a></li>
                    </ul>
                </div>

            </div>

            <!-- Baris Bawah Copyright -->
            <div class="pt-5 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                <div>
                    &copy; 2020 &ndash; 2026 <strong>IDMETAFORA / MetaforaCRM</strong>. Seluruh hak cipta dilindungi undang-undang.
                </div>
                <div class="flex items-center gap-6">
                    <a href="<?= base_url('login') ?>" class="hover:text-rose-400 transition-colors">Portal Admin</a>
                    <a href="#hero" class="text-rose-400 hover:text-rose-300 font-bold transition-colors">Kembali ke Atas ↑</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- ================================================================= -->
    <!-- INTERACTIVE JAVASCRIPT                                            -->
    <!-- ================================================================= -->
    <script>
        // Mobile Drawer Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            document.querySelectorAll('.mobile-item').forEach(item => {
                item.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });
        }

        // FAQ Accordion Toggle
        document.querySelectorAll('.faq-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('.faq-icon');
                const isHidden = answer.classList.contains('hidden');

                // Close other open answers
                document.querySelectorAll('.faq-answer').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.faq-icon').forEach(el => {
                    el.textContent = '+';
                    el.classList.remove('rotate-45');
                });

                if (isHidden) {
                    answer.classList.remove('hidden');
                    icon.textContent = '−';
                }
            });
        });
    </script>
</body>

</html>

