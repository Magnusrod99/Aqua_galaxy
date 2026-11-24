<?php
$year = date("Y");
?>
<footer class="bg-slate-900 text-slate-300 pt-16 pb-28 md:pb-12 px-4 sm:px-6 lg:px-8 mt-auto border-t border-slate-800">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            <!-- Col 1: Brand & Bio -->
            <div class="space-y-4">
                <a href="<?= BASE_URL ?>/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-sky-500/20 border border-sky-400/30 flex items-center justify-center text-2xl">
                        🐟
                    </div>
                    <span class="font-extrabold text-2xl text-white">
                        Aqua <span class="text-sky-400">Galaxy</span>
                    </span>
                </a>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Your trusted destination for ornamental aquatic life, aquarium gear, and bespoke ladies' tailoring. Located proudly in Thivim, North Goa.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-semibold border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Shop Open in Thivim
                    </span>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-4 border-l-2 border-sky-400 pl-3">
                    Quick Navigation
                </h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="<?= BASE_URL ?>/" class="hover:text-sky-400 transition flex items-center gap-2"><span>•</span> Home</a></li>
                    <li><a href="<?= BASE_URL ?>/fish.php" class="hover:text-sky-400 transition flex items-center gap-2"><span>•</span> Fish Collection</a></li>
                    <li><a href="<?= BASE_URL ?>/products.php" class="hover:text-sky-400 transition flex items-center gap-2"><span>•</span> Aquarium Products</a></li>
                    <li><a href="<?= BASE_URL ?>/tailoring.php" class="hover:text-sky-400 transition flex items-center gap-2"><span>•</span> Ladies Tailoring</a></li>
                    <li><a href="<?= BASE_URL ?>/gallery.php" class="hover:text-sky-400 transition flex items-center gap-2"><span>•</span> Photo Gallery</a></li>
                    <li><a href="<?= BASE_URL ?>/contact.php" class="hover:text-sky-400 transition flex items-center gap-2"><span>•</span> Contact & Directions</a></li>
                </ul>
            </div>

            <!-- Col 3: Services & Offerings -->
            <div>
                <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-4 border-l-2 border-sky-400 pl-3">
                    Our Specialties
                </h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li class="flex items-start gap-2">
                        <span class="text-sky-400">✓</span> Exotic & Hardy Ornamental Fish
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sky-400">✓</span> High-grade Aquarium Food & Feed
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sky-400">✓</span> Tanks, Filtration & Aerators
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sky-400">✓</span> Custom Blouse & Kurti Stitching
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sky-400">✓</span> Precision Dress Alterations & Fit
                    </li>
                </ul>
            </div>

            <!-- Col 4: Contact & Hours -->
            <div>
                <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-4 border-l-2 border-sky-400 pl-3">
                    Visit Our Store
                </h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3 text-slate-300">
                        <span class="text-sky-400 text-lg">📍</span>
                        <span>Thivim, North Goa, India</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-sky-400 text-lg">📞</span>
                        <a href="tel:+918806048011" class="text-white hover:text-sky-400 font-semibold transition">
                            +91 88060 48011
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-sky-400 text-lg">✉️</span>
                        <a href="mailto:bentonmonteiro@gmail.com" class="text-slate-300 hover:text-white transition">
                            bentonmonteiro@gmail.com
                        </a>
                    </li>
                    <li class="pt-2 text-xs text-slate-400 flex items-center gap-2">
                        <span class="text-sky-400">⏰</span>
                        <span>Mon - Sat: 9:30 AM – 8:30 PM (Sun: On Call)</span>
                    </li>
                </ul>
            </div>

        </div>

        <div class="border-t border-slate-800 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <div>
                © <?= $year ?> Aqua Galaxy. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <span>Aquatic Elegance • Expert Tailoring</span>
                <span class="text-slate-700">•</span>
                <a href="<?= BASE_URL ?>/admin/login.php" class="text-slate-600 hover:text-slate-400 transition">Owner Login</a>
            </div>
        </div>
    </div>
</footer>

<!-- Mobile Floating Dock (Call & WhatsApp) - Permanent UX on Phones -->
<div class="mobile-dock md:hidden">
    <a href="tel:+918806048011" class="bg-sky-500 hover:bg-sky-600 active:bg-sky-700">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6.6 10.8a15 15 0 006.6 6.6l2.2-2.2a1 1 0 011-.25c1.1.35 2.3.55 3.6.55a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.3.2 2.5.6 3.6a1 1 0 01-.25 1z"/></svg>
        <span>Call Store</span>
    </a>
    <a href="https://wa.me/918806048011?text=Hello%20Aqua%20Galaxy,%20I%20am%20interested%20in%20your%20services." 
       target="_blank" rel="noopener"
       class="bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm5.2 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.2-4.6-4-4.7-4.2-.1-.2-1.1-1.5-1.1-2.8s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 2c.1.2.1.4 0 .5l-.4.6-.3.4c-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.3 2.4 1.5.3.1.5.1.6-.1l.9-1c.2-.3.4-.2.6-.1l1.9.9c.3.1.5.2.5.3.1.2.1.8-.1 1.3z"/></svg>
        <span>WhatsApp</span>
    </a>
</div>

</body>
</html>
