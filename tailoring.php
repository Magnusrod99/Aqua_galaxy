<?php
$pageTitle = "Ladies' Tailoring & Alteration Services | Aqua Galaxy";

require_once __DIR__ . "/config/app.php";
require_once __DIR__ . "/config/database.php";

$tailoringList = [];

if (isset($pdo) && $pdo !== null) {
    try {
        $stmt = $pdo->query("SELECT * FROM tailoring_services WHERE status = 1 ORDER BY id ASC");
        $tailoringList = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Tailoring query error: " . $e->getMessage());
    }
}

// Fallback curated services list
if (empty($tailoringList)) {
    $tailoringList = [
        [
            'id' => 1,
            'name' => 'Designer Saree Blouse Stitching',
            'category' => 'Blouses',
            'description' => 'Precision tailored blouses with perfect cup fit, princess cuts, boat necks, backless designs, and lining fabric finish.',
            'price' => 450,
            'price_type' => 'Starting from',
            'turnaround' => '3 – 5 Days',
            'features' => ['Custom Necklines & Latkans', 'Padded or Non-Padded', 'Reinforced Hook Plates']
        ],
        [
            'id' => 2,
            'name' => 'Salwar Suit & Churidar Tailoring',
            'category' => 'Suits & Kurtis',
            'description' => 'Traditional Punjabi suits, straight-cut palazzo sets, and flared Anarkalis tailored comfortably to your exact body measurements.',
            'price' => 600,
            'price_type' => 'Starting from',
            'turnaround' => '4 – 6 Days',
            'features' => ['Dupatta finishing included', 'Custom sleeve styling', 'Breathable seam stitching']
        ],
        [
            'id' => 3,
            'name' => 'Everyday & Festive Kurti Stitching',
            'category' => 'Suits & Kurtis',
            'description' => 'Chic A-line kurtis, office wear tunics, and festive designer kurtas with side slits, pocket additions, and delicate piping.',
            'price' => 380,
            'price_type' => 'Starting from',
            'turnaround' => '3 – 4 Days',
            'features' => ['Pockets can be added', 'Comfortable daily wear fit', 'All fabric types welcome']
        ],
        [
            'id' => 4,
            'name' => 'Western & Indo-Western Gowns',
            'category' => 'Gowns & Dresses',
            'description' => 'Custom stitched evening party gowns, maxi dresses, flared skirts, and birthday outfits designed for an elegant drape and flow.',
            'price' => 950,
            'price_type' => 'Starting from',
            'turnaround' => '5 – 7 Days',
            'features' => ['Flared can-can lining support', 'Concealed zip finishes', 'Full length custom styling']
        ],
        [
            'id' => 5,
            'name' => 'Precision Alterations & Size Adjustments',
            'category' => 'Alterations & Pico',
            'description' => 'Bought an outfit online that doesn’t fit? We resize, nip the waist, alter shoulder straps, and adjust lengths with invisible stitches.',
            'price' => 120,
            'price_type' => 'Starting from',
            'turnaround' => 'Same Day / 24h',
            'features' => ['Jeans & trousers hem', 'Dress tightening & resizing', 'Zip & button replacement']
        ],
        [
            'id' => 6,
            'name' => 'Saree Fall Stitching & Pico Edging',
            'category' => 'Alterations & Pico',
            'description' => 'Neat edge overlocking, zigzag pico hem, and secure hand-stitched fall to give your silk and cotton sarees a flawless drape.',
            'price' => 100,
            'price_type' => 'Starting from',
            'turnaround' => '24 – 48 Hours',
            'features' => ['Color-matched cotton falls', 'Pico on both saree edges', 'Gentle fabric care']
        ]
    ];
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="pt-24 sm:pt-28 min-h-screen">

    <!-- Hero Header -->
    <section class="bg-gradient-to-b from-slate-900 via-pink-950 to-slate-900 text-white py-12 sm:py-16 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-pink-500/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-pink-500/20 text-pink-300 text-xs font-semibold tracking-wide border border-pink-400/20 mb-4 backdrop-blur-md">
                <span>✂️</span> Bespoke Women's Garments in Thivim
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                Ladies' <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-300 to-rose-400">Tailoring Services</span>
            </h1>
            <p class="mt-3 text-slate-300 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Expert custom stitching, perfect-fit saree blouses, salwar suits, and quick alterations. Quality craftsmanship for every special occasion.
            </p>

            <div class="mt-8 grid grid-cols-3 gap-2 max-w-md mx-auto text-center">
                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                    <p class="text-xs text-pink-300 font-bold">Perfect Fit</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Trial & Adjustments</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                    <p class="text-xs text-rose-300 font-bold">Fast Service</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Express Turnaround</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                    <p class="text-xs text-amber-300 font-bold">Fair Rates</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Transparent Pricing</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-8 scrollbar-none">
            <button type="button" class="tailor-cat-btn active-t-cat px-4 py-2 rounded-full text-xs font-bold bg-slate-900 text-white shadow-sm transition whitespace-nowrap" data-tcat="all">
                All Services
            </button>
            <button type="button" class="tailor-cat-btn px-4 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition whitespace-nowrap" data-tcat="blouses">
                Saree Blouses
            </button>
            <button type="button" class="tailor-cat-btn px-4 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition whitespace-nowrap" data-tcat="suits">
                Suits &amp; Kurtis
            </button>
            <button type="button" class="tailor-cat-btn px-4 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition whitespace-nowrap" data-tcat="gowns">
                Gowns &amp; Dresses
            </button>
            <button type="button" class="tailor-cat-btn px-4 py-2 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition whitespace-nowrap" data-tcat="alterations">
                Alterations &amp; Pico
            </button>
        </div>

        <div id="tailorGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($tailoringList as $item): 
                $category = $item['category'] ?? 'Tailoring';
                $priceDisplay = !empty($item['price']) ? '₹' . number_format($item['price']) : 'Affordable';
                $pricePrefix = $item['price_type'] ?? 'Starts from';
                $turnaround = $item['turnaround'] ?? '3 – 5 Days';
                $waMessage = rawurlencode("Hello Aqua Galaxy Tailoring, I would like to inquire about: " . $item['name'] . ". When can I bring my fabric for measurement in Thivim?");
            ?>
                <article 
                    class="tailor-card bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between card-hover"
                    data-category="<?= htmlspecialchars(strtolower($category)) ?>"
                >
                    <div class="p-6">
                        
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-pink-50 text-pink-700 border border-pink-100">
                                <?= htmlspecialchars($category) ?>
                            </span>

                            <span class="text-xs text-slate-400 font-semibold flex items-center gap-1">
                                <span>⏱️</span>
                                <span><?= htmlspecialchars($turnaround) ?></span>
                            </span>
                        </div>

                        <h3 class="font-extrabold text-xl text-slate-900 tracking-tight mt-4 leading-snug">
                            <?= htmlspecialchars($item['name']) ?>
                        </h3>

                        <p class="text-sm text-slate-500 mt-2.5 leading-relaxed">
                            <?= htmlspecialchars($item['description']) ?>
                        </p>

                        <!-- Key Features -->
                        <?php if (!empty($item['features']) && is_array($item['features'])): ?>
                            <ul class="mt-4 space-y-1.5 text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <?php foreach ($item['features'] as $feature): ?>
                                    <li class="flex items-center gap-2">
                                        <span class="text-pink-500 font-bold">✓</span>
                                        <span><?= htmlspecialchars($feature) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                    </div>

                    <!-- Footer & Pricing -->
                    <div class="p-6 pt-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">
                                <?= htmlspecialchars($pricePrefix) ?>
                            </span>
                            <span class="text-xl font-black text-slate-900 tracking-tight">
                                <?= $priceDisplay ?>
                            </span>
                        </div>

                        <a href="https://wa.me/918806048011?text=<?= $waMessage ?>"
                           target="_blank" rel="noopener"
                           class="bg-pink-600 hover:bg-pink-700 active:bg-pink-800 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md shadow-pink-600/20 flex items-center gap-1.5 transition transform active:scale-95">
                            <span>Book / Inquire</span>
                            <span>💬</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

    </section>

    <!-- How It Works Section (Mobile-friendly steps) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm">
            
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="text-xs font-bold text-pink-600 uppercase tracking-wider">Simple Process</span>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                    How We Tailor Your Outfits
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">
                    Friendly, precise, and hassle-free tailoring in four easy steps.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 text-center flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 font-black text-lg flex items-center justify-center mb-3">
                        1
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Bring Your Fabric</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Visit our Thivim shop with your material, saree, or outfit for alteration.
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 text-center flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 font-black text-lg flex items-center justify-center mb-3">
                        2
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Measurements &amp; Design</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Choose necklines, cuts, and sleeves or give us a sample fitting blouse.
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 text-center flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 font-black text-lg flex items-center justify-center mb-3">
                        3
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Master Stitching</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Precision cutting with neat seams, quality lining, and clean finishes.
                    </p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 text-center flex flex-col items-center">
                    <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 font-black text-lg flex items-center justify-center mb-3">
                        4
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Trial &amp; Collection</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Try your outfit on. Any minor adjustments are done happily on the spot!
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- Consultation Card -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-gradient-to-r from-pink-600 via-rose-600 to-pink-700 rounded-3xl p-6 sm:p-10 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-bold mb-3 tracking-wide">
                    👗 Wedding or Festival Coming Up?
                </span>
                <h3 class="text-2xl sm:text-3xl font-black tracking-tight leading-snug">
                    Book Your Tailoring Slot in Advance
                </h3>
                <p class="text-pink-100 text-sm mt-2 leading-relaxed">
                    Have a special event, wedding celebration, or party in Goa? Message us your dates and design ideas to ensure timely delivery.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="https://wa.me/918806048011?text=Hello%20Aqua%20Galaxy,%20I%20would%20like%20to%20book%20a%20tailoring%20consultation:"
                   target="_blank" rel="noopener"
                   class="bg-white text-slate-900 hover:bg-pink-50 px-6 py-3.5 rounded-2xl text-xs font-bold text-center shadow-lg transition">
                    Message on WhatsApp
                </a>
                <a href="tel:+918806048011"
                   class="bg-pink-900/40 hover:bg-pink-900/60 border border-white/20 text-white px-6 py-3.5 rounded-2xl text-xs font-bold text-center transition">
                    Call: +91 88060 48011
                </a>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const catButtons = document.querySelectorAll(".tailor-cat-btn");
    const cards = document.querySelectorAll(".tailor-card");

    catButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            catButtons.forEach(b => {
                b.classList.remove("active-t-cat", "bg-slate-900", "text-white");
                b.classList.add("bg-white", "text-slate-700");
            });

            this.classList.add("active-t-cat", "bg-slate-900", "text-white");
            this.classList.remove("bg-white", "text-slate-700");

            const selectedCat = this.dataset.tcat;

            cards.forEach(card => {
                const cardCat = card.dataset.category || "";
                if (selectedCat === "all" || cardCat.includes(selectedCat)) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
