
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-sky-100">

    <div class="max-w-7xl mx-auto px-6 md:px-14 lg:px-24 py-4 flex items-center justify-between">

        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-sky-100 border-2 border-sky-300 flex items-center justify-center text-2xl">
                🐟
            </div>

            <span class="font-bold text-2xl text-slate-900">
                Aqua <span class="text-sky-500">Galaxy</span>
            </span>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-8">
            <a href="<?= BASE_URL ?>/" class="text-slate-600 hover:text-sky-500 transition">Home</a>
            <a href="<?= BASE_URL ?>/fish.php" class="text-slate-600 hover:text-sky-500 transition">Fish</a>
            <a href="<?= BASE_URL ?>/tailoring.php" class="text-slate-600 hover:text-sky-500 transition">Tailoring</a>
            <a href="<?= BASE_URL ?>/contact.php" class="text-slate-600 hover:text-sky-500 transition">Contact</a>

            <a href="tel:+918806048011"
               class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold transition">
                Call Us
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <button
            id="menuBtn"
            type="button"
            aria-label="Open navigation menu"
            aria-expanded="false"
            aria-controls="mobileMenu"
            class="md:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5"
        >
            <span class="w-6 h-px bg-slate-700"></span>
            <span class="w-6 h-px bg-slate-700"></span>
            <span class="w-6 h-px bg-slate-700"></span>
        </button>

    </div>

    <!-- Mobile Navigation -->
    <div id="mobileMenu" class="hidden md:hidden border-t border-sky-100 bg-white shadow-lg px-6 py-5">
        <div class="flex flex-col gap-5">

            <a href="<?= BASE_URL ?>/" class="text-slate-600 hover:text-sky-500 transition">Home</a>
            <a href="<?= BASE_URL ?>/fish.php" class="text-slate-600 hover:text-sky-500 transition">Fish</a>
            <a href="<?= BASE_URL ?>/tailoring.php" class="text-slate-600 hover:text-sky-500 transition">Tailoring</a>
            <a href="<?= BASE_URL ?>/contact.php" class="text-slate-600 hover:text-sky-500 transition">Contact</a>

            <a href="tel:+918806048011"
               class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-full text-sm font-semibold text-center transition">
                Call Us
            </a>

        </div>
    </div>

</nav>
