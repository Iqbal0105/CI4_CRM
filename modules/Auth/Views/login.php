<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Portal - MetaforaCRM &amp; ERP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-[#070C1B] text-slate-100 font-sans min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden selection:bg-rose-600 selection:text-white">

    <!-- Ambient Crimson Glow Background -->
    <div class="fixed top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-rose-600/15 blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-red-600/10 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 py-6">

        <!-- Top Brand Navigation Link -->
        <div class="text-center mb-6">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-red-600 to-rose-600 flex items-center justify-center text-white shadow-lg shadow-rose-600/30 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <span class="font-extrabold text-2xl tracking-tight text-white font-heading">
                    Metafora<span class="text-rose-500">CRM</span>
                </span>
            </a>
            <p class="text-xs text-slate-400 mt-1">Sistem Terintegrasi ERP, POS &amp; CRM WhatsApp</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 text-slate-800 shadow-2xl border border-slate-100">
            <div class="mb-6 text-center">
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0F172A] font-heading">Selamat Datang Kembali</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Silakan masukkan akun Anda untuk mengakses dashboard
                </p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm mt-0.5 shrink-0"></i>
                    <div><?= esc(session()->getFlashdata('error')) ?></div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                    <div><?= esc(session()->getFlashdata('success')) ?></div>
                </div>
            <?php endif; ?>

            <?php if ($errors = session()->getFlashdata('errors')): ?>
                <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    <div class="font-bold flex items-center gap-1.5 mb-1.5">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Terjadi kesalahan:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-slate-600">
                        <?php foreach ((array)$errors as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= base_url('login') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Username or Email -->
                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 mb-1.5 font-heading">
                        Username atau Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm pointer-events-none">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" id="login" name="login" value="<?= old('login') ?>" required autofocus
                            placeholder="admin / kasir / email Anda"
                            class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all bg-slate-50/50">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 font-heading">
                            Kata Sandi (Password) <span class="text-rose-500">*</span>
                        </label>
                        <a href="https://wa.me/6289664230232?text=Halo%20Admin%2C%20saya%20lupa%20kata%20sandi%20akun%20saya." target="_blank" class="text-[11px] text-rose-600 hover:underline">
                            Lupa sandi?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm pointer-events-none">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all bg-slate-50/50">
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 text-xs">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:to-rose-500 shadow-lg shadow-rose-900/30 transition-all hover:-translate-y-0.5 active:translate-y-0 font-heading flex items-center justify-center gap-2">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Bottom Links -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
                <div>
                    Belum punya akun?
                    <a href="<?= base_url('register') ?>" class="text-rose-600 font-bold hover:underline ml-1">Daftar Sekarang</a>
                </div>
                <div>
                    <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-slate-800 transition-colors">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
