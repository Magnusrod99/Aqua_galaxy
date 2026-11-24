<?php
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');

$navLinks = [
    ['href' => BASE_URL . '/', 'label' => 'Home', 'file' => 'index.php'],
    ['href' => BASE_URL . '/fish.php', 'label' => 'Fish Collection', 'file' => 'fish.php'],
    ['href' => BASE_URL . '/products.php', 'label' => 'Products', 'file' => 'products.php'],
    ['href' => BASE_URL . '/tailoring.php', 'label' => 'Ladies Tailoring', 'file' => 'tailoring.php'],
    ['href' => BASE_URL . '/gallery.php', 'label' => 'Gallery', 'file' => 'gallery.php'],
    ['href' => BASE_URL . '/contact.php', 'label' => 'Contact', 'file' => 'contact.php'],
];
?>

<nav class="fixed top-0 left-0 right-0 z-50 glass-nav border-b border-sky-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between py-3">

        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" class="flex items-center gap-3 group focus:outline-none" aria-label="Aqua Galaxy Home">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-sky-500 to-cyan-400 p-0.5 shadow-md shadow-sky-500/20 group-hover:scale-105 transition-transform duration-200">
                <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center text-xl">
                    🐟
                </div>
            </div>
            <div class="flex flex-col">
                <span class="font-extrabold text-xl sm:text-2xl tracking-tight text-slate-900 leading-tight">
                    Aqua <span class="text-sky-500">Galaxy</span>
                </span>
                <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase -mt-0.5">Thivim, North Goa</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center gap-1.5">
            <?php foreach ($navLinks as $link): 
                $isActive = ($currentPage === $link['file']);
            ?>
                <a href="<?= $link['href'] ?>" 
                   class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-all duration-150 <?= $isActive ? 'text-sky-600 bg-sky-50 shadow-sm' : 'text-slate-600 hover:text-sky-600 hover:bg-slate-50' ?>">
                    <?= htmlspecialchars($link['label']) ?>
                </a>
            <?php endforeach; ?>

            <div class="h-5 w-px bg-slate-200 mx-2"></div>

            <a href="tel:+918806048011"
               class="bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white px-5 py-2.5 rounded-full text-xs font-bold shadow-md shadow-sky-500/20 hover:shadow-lg transition-all transform active:scale-95 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M6.6 10.8a15 15 0 006.6 6.6l2.2-2.2a1 1 0 011-.25c1.1.35 2.3.55 3.6.55a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.3.2 2.5.6 3.6a1 1 0 01-.25 1z"/></svg>
                <span>Call Us</span>
            </a>
        </div>

        <!-- Mobile & Tablet Menu Button -->
        <button
            id="menuBtn"
            type="button"
            aria-label="Toggle navigation menu"
            aria-expanded="false"
            aria-controls="mobileMenu"
            class="lg:hidden w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex flex-col items-center justify-center gap-1 transition focus:outline-none"
        >
            <span class="w-5 h-0.5 bg-slate-700 rounded-full transition-transform" id="bar1"></span>
            <span class="w-5 h-0.5 bg-slate-700 rounded-full transition-opacity" id="bar2"></span>
            <span class="w-5 h-0.5 bg-slate-700 rounded-full transition-transform" id="bar3"></span>
        </button>

    </div>

    <!-- Mobile Drawer Navigation -->
    <div id="mobileMenu" class="hidden lg:hidden border-t border-sky-100 bg-white/98 backdrop-blur-xl shadow-xl px-4 py-4 max-h-[calc(100vh-72px)] overflow-y-auto">
        <div class="flex flex-col gap-1.5">
            <?php foreach ($navLinks as $link): 
                $isActive = ($currentPage === $link['file']);
            ?>
                <a href="<?= $link['href'] ?>" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-base font-semibold transition <?= $isActive ? 'text-sky-600 bg-sky-50 font-bold' : 'text-slate-700 hover:bg-slate-50' ?>">
                    <span><?= htmlspecialchars($link['label']) ?></span>
                    <?php if ($isActive): ?>
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    <?php else: ?>
                        <span class="text-slate-300 text-sm">→</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>

            <div class="pt-3 mt-2 border-t border-slate-100 flex flex-col gap-2">
                <a href="tel:+918806048011"
                   class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-3 rounded-xl text-sm font-bold text-center shadow-md shadow-sky-500/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6.6 10.8a15 15 0 006.6 6.6l2.2-2.2a1 1 0 011-.25c1.1.35 2.3.55 3.6.55a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.3.2 2.5.6 3.6a1 1 0 01-.25 1z"/></svg>
                    <span>Direct Call (+91 88060 48011)</span>
                </a>
                <a href="https://wa.me/918806048011"
                   target="_blank" rel="noopener"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl text-sm font-bold text-center shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2">
                    <span>Chat on WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("menuBtn");
    const menu = document.getElementById("mobileMenu");
    if (!btn || !menu) return;

    btn.addEventListener("click", function () {
        const isClosed = menu.classList.contains("hidden");
        menu.classList.toggle("hidden", !isClosed);
        btn.setAttribute("aria-expanded", String(isClosed));
    });

    menu.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            menu.classList.add("hidden");
            btn.setAttribute("aria-expanded", "false");
        });
    });
});
</script>
