<?php
$pageTitle = "Ornamental Fish Collection | Aqua Galaxy";

require_once __DIR__ . "/config/app.php";
require_once __DIR__ . "/config/database.php";

// Fetch categories and fish from database or use curated fallback if DB is empty/offline
$categories = [];
$fishList = [];

if (isset($pdo) && $pdo !== null) {
    try {
        $catStmt = $pdo->query("SELECT id, name, slug FROM fish_categories WHERE status = 1 ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        $fishStmt = $pdo->query("
            SELECT f.*, c.name AS category_name 
            FROM fish f
            LEFT JOIN fish_categories c ON f.category_id = c.id
            WHERE f.status = 1
            ORDER BY f.id DESC
        ");
        $fishList = $fishStmt->fetchAll();
    } catch (Exception $e) {
        error_log("Fish page query failed: " . $e->getMessage());
    }
}

// If no records in database yet, provide a rich, realistic catalog for instant client demonstration
if (empty($fishList)) {
    $categories = [
        ['id' => 1, 'name' => 'Goldfish', 'slug' => 'goldfish'],
        ['id' => 2, 'name' => 'Livebearers & Guppies', 'slug' => 'livebearers'],
        ['id' => 3, 'name' => 'Tetras & Rasboras', 'slug' => 'tetras'],
        ['id' => 4, 'name' => 'Cichlids & Angelfish', 'slug' => 'cichlids'],
        ['id' => 5, 'name' => 'Betta (Fighter)', 'slug' => 'betta'],
        ['id' => 6, 'name' => 'Shrimp & Bottom Dwellers', 'slug' => 'shrimp'],
    ];

    $fishList = [
        [
            'id' => 1,
            'name' => 'Red Cap Oranda Goldfish',
            'slug' => 'red-cap-oranda',
            'category_name' => 'Goldfish',
            'category_id' => 1,
            'description' => 'Gentle and distinctive with its iconic red crown cap. Extremely popular, active, and peaceful community favorite.',
            'price_min' => 250,
            'price_max' => 600,
            'care_level' => 'easy',
            'tank_size' => '20+ Gallons',
            'temperature' => '18°C - 24°C',
            'food' => 'Sinking Pellets & Greens',
            'image' => null,
            'badge' => 'Bestseller'
        ],
        [
            'id' => 2,
            'name' => 'Albino Full Red Dumbo Guppy',
            'slug' => 'dumbo-ear-guppy',
            'category_name' => 'Livebearers & Guppies',
            'category_id' => 2,
            'description' => 'Spectacular oversized pectoral fins resembling elephant ears with vivid red hues. Peaceful and prolific breeders.',
            'price_min' => 180,
            'price_max' => 350,
            'care_level' => 'easy',
            'tank_size' => '10+ Gallons',
            'temperature' => '22°C - 28°C',
            'food' => 'Micro Pellets, Flakes',
            'image' => null,
            'badge' => 'Show Grade'
        ],
        [
            'id' => 3,
            'name' => 'Neon Tetra (Schooling Fish)',
            'slug' => 'neon-tetra',
            'category_name' => 'Tetras & Rasboras',
            'category_id' => 3,
            'description' => 'Radiant electric blue and crimson red iridescent stripe. Thrives beautifully in planted community aquariums in groups.',
            'price_min' => 40,
            'price_max' => 80,
            'care_level' => 'easy',
            'tank_size' => '15+ Gallons',
            'temperature' => '20°C - 26°C',
            'food' => 'Small Flakes, Artemia',
            'image' => null,
            'badge' => 'Top Community'
        ],
        [
            'id' => 4,
            'name' => 'Halfmoon Plakat (HMPK) Betta',
            'slug' => 'hmpk-betta',
            'category_name' => 'Betta (Fighter)',
            'category_id' => 5,
            'description' => 'Vibrant coloration, strong finnage, and full 180-degree tail spread. Healthy, alert, and active personalities.',
            'price_min' => 250,
            'price_max' => 750,
            'care_level' => 'easy',
            'tank_size' => '5+ Gallons',
            'temperature' => '24°C - 30°C',
            'food' => 'Betta Bites, Bloodworms',
            'image' => null,
            'badge' => 'Handpicked'
        ],
        [
            'id' => 5,
            'name' => 'Blue Diamond Angelfish',
            'slug' => 'blue-diamond-angelfish',
            'category_name' => 'Cichlids & Angelfish',
            'category_id' => 4,
            'description' => 'Graceful, diamond-patterned scales with shimmering icy blue highlights. The centerpiece for tall aquarium setups.',
            'price_min' => 220,
            'price_max' => 550,
            'care_level' => 'medium',
            'tank_size' => '30+ Gallons',
            'temperature' => '24°C - 29°C',
            'food' => 'Granules, Live Daphnia',
            'image' => null,
            'badge' => 'Centerpiece'
        ],
        [
            'id' => 6,
            'name' => 'Red Cherry Dwarf Shrimp',
            'slug' => 'red-cherry-shrimp',
            'category_name' => 'Shrimp & Bottom Dwellers',
            'category_id' => 6,
            'description' => 'Bright red, busy algae-eaters. Excellent for nano planted tanks, gentle on plants, and fun to observe cleaning your tank.',
            'price_min' => 50,
            'price_max' => 90,
            'care_level' => 'easy',
            'tank_size' => '5+ Gallons',
            'temperature' => '21°C - 27°C',
            'food' => 'Algae Wafers, Biofilm',
            'image' => null,
            'badge' => 'Nano Favorite'
        ]
    ];
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="pt-24 sm:pt-28 min-h-screen">

    <!-- Hero Header -->
    <section class="bg-gradient-to-b from-sky-900 via-sky-950 to-slate-900 text-white py-12 sm:py-16 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-sky-500/20 text-cyan-300 text-xs font-semibold tracking-wide border border-sky-400/20 mb-4 backdrop-blur-md">
                <span>🐠</span> Premium Aquatic Life in Goa
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                Ornamental <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-cyan-400">Fish Collection</span>
            </h1>
            <p class="mt-3 text-slate-300 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Healthy, quarantined, and acclimated ornamental fish, exotic shrimps, and community favorites. Ready for your home aquarium.
            </p>

            <!-- Quick stats bar on mobile -->
            <div class="mt-8 grid grid-cols-3 gap-2 max-w-md mx-auto text-center">
                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                    <p class="text-xs text-sky-300 font-bold">100% Healthy</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Quarantined Stock</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                    <p class="text-xs text-cyan-300 font-bold">Expert Advice</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tank Setup Tips</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                    <p class="text-xs text-emerald-300 font-bold">Direct Pickup</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Thivim, North Goa</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter & Search Controls -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-4 sm:p-5">
            <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
                
                <!-- Search Box -->
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                        🔍
                    </span>
                    <input 
                        type="text" 
                        id="fishSearch" 
                        placeholder="Search by name (e.g. Guppy, Betta, Goldfish)..."
                        class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                    >
                    <button id="clearSearch" type="button" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 text-sm font-bold">
                        ✕
                    </button>
                </div>

                <!-- Care Level Quick Filter -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap pl-1">Care:</span>
                    <button type="button" class="care-filter-btn active-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-800 text-white transition whitespace-nowrap" data-care="all">
                        All Levels
                    </button>
                    <button type="button" class="care-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition whitespace-nowrap" data-care="easy">
                        Easy
                    </button>
                    <button type="button" class="care-filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition whitespace-nowrap" data-care="medium">
                        Medium
                    </button>
                </div>

            </div>

            <!-- Category Pills (Horizontal scroll for phones) -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2 overflow-x-auto pb-1 text-sm scrollbar-none">
                <button type="button" class="cat-filter-btn active-cat px-3.5 py-1.5 rounded-full text-xs font-bold bg-sky-500 text-white shadow-sm transition whitespace-nowrap" data-cat="all">
                    All Fish
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button type="button" class="cat-filter-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition whitespace-nowrap" data-cat="<?= htmlspecialchars(strtolower($cat['name'])) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Fish Grid Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Available Live Stock
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5" id="resultsCount">
                    Showing <?= count($fishList) ?> varieties
                </p>
            </div>
            
            <a href="https://wa.me/918806048011?text=Hi%20Aqua%20Galaxy,%20do%20you%20have%20any%20new%20fish%20arrivals%20today?" 
               target="_blank" rel="noopener"
               class="text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1.5 bg-sky-50 px-3 py-2 rounded-xl border border-sky-100 hover:border-sky-200 transition">
                <span>Check Today's Arrival</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <!-- Fish Cards Grid -->
        <div id="fishGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($fishList as $fish): 
                $careClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                $careLabel = 'Easy Care';
                if (($fish['care_level'] ?? '') === 'medium') {
                    $careClass = 'bg-amber-50 text-amber-700 border-amber-200';
                    $careLabel = 'Moderate Care';
                } elseif (($fish['care_level'] ?? '') === 'hard') {
                    $careClass = 'bg-rose-50 text-rose-700 border-rose-200';
                    $careLabel = 'Expert Care';
                }

                $priceText = '';
                if (!empty($fish['price_min']) && !empty($fish['price_max']) && (float)$fish['price_min'] !== (float)$fish['price_max']) {
                    $priceText = '₹' . number_format($fish['price_min']) . ' – ₹' . number_format($fish['price_max']);
                } elseif (!empty($fish['price_min'])) {
                    $priceText = '₹' . number_format($fish['price_min']);
                } else {
                    $priceText = 'Price on Request';
                }

                $categoryName = $fish['category_name'] ?? 'Aquatic';
                $waMessage = rawurlencode("Hello Aqua Galaxy, I am interested in: " . $fish['name'] . " (" . $priceText . "). Is it currently in stock at Thivim?");
            ?>
                <article 
                    class="fish-card bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col card-hover"
                    data-name="<?= htmlspecialchars(strtolower($fish['name'])) ?>"
                    data-category="<?= htmlspecialchars(strtolower($categoryName)) ?>"
                    data-care="<?= htmlspecialchars(strtolower($fish['care_level'] ?? 'easy')) ?>"
                >
                    <!-- Visual Top Header / Image Area -->
                    <div class="h-48 sm:h-52 bg-gradient-to-tr from-slate-900 via-sky-950 to-sky-800 relative flex items-center justify-center overflow-hidden">
                        
                        <?php if (!empty($fish['image']) && file_exists(__DIR__ . '/uploads/fish/' . $fish['image'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/fish/<?= htmlspecialchars($fish['image']) ?>" 
                                 alt="<?= htmlspecialchars($fish['name']) ?>" 
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <!-- Illustrated Aquatic Accent Visual -->
                            <div class="relative w-full h-full flex flex-col items-center justify-center p-6 text-center select-none">
                                <div class="w-20 h-20 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-4xl shadow-inner backdrop-blur-sm group-hover:scale-110 transition">
                                    🐟
                                </div>
                                <span class="text-xs text-sky-200/80 font-medium tracking-wide mt-2">
                                    Aqua Galaxy Live Stock
                                </span>
                            </div>
                        <?php endif; ?>

                        <!-- Care Badge -->
                        <span class="absolute top-3 left-3 text-[11px] font-bold px-2.5 py-1 rounded-full border backdrop-blur-md shadow-sm <?= $careClass ?>">
                            ● <?= $careLabel ?>
                        </span>

                        <!-- Category Pill -->
                        <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/90 text-slate-800 shadow-sm backdrop-blur-md">
                            <?= htmlspecialchars($categoryName) ?>
                        </span>
                    </div>

                    <!-- Content Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-extrabold text-lg text-slate-900 tracking-tight leading-snug">
                                    <?= htmlspecialchars($fish['name']) ?>
                                </h3>
                            </div>

                            <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed line-clamp-2">
                                <?= htmlspecialchars($fish['description'] ?: 'High quality, vibrant ornamental fish ready for well-cycled freshwater aquarium environments.') ?>
                            </p>

                            <!-- Specifications Quick Grid -->
                            <div class="mt-4 grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Tank Requirement</span>
                                    <span class="font-semibold text-slate-700"><?= htmlspecialchars($fish['tank_size'] ?: '10+ Gallons') ?></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] uppercase font-bold">Water Temp</span>
                                    <span class="font-semibold text-slate-700"><?= htmlspecialchars($fish['temperature'] ?: '22°C - 28°C') ?></span>
                                </div>
                                <div class="col-span-2 pt-1 border-t border-slate-200/60 mt-1 flex items-center gap-1.5 text-slate-600">
                                    <span class="text-sky-500 font-bold">Diet:</span>
                                    <span><?= htmlspecialchars($fish['food'] ?: 'Flakes, Micro Pellets & Live Food') ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Price & Action Footer -->
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Estimated Price</span>
                                <span class="text-lg font-black text-slate-900 tracking-tight">
                                    <?= $priceText ?>
                                </span>
                            </div>

                            <a href="https://wa.me/918806048011?text=<?= $waMessage ?>"
                               target="_blank" rel="noopener"
                               class="bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition transform active:scale-95">
                                <span>Inquire</span>
                                <span class="text-emerald-200">💬</span>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- No Results Fallback -->
        <div id="noResults" class="hidden text-center py-16 bg-white rounded-3xl border border-slate-200 my-8">
            <div class="text-5xl mb-3">🐠</div>
            <h3 class="text-lg font-bold text-slate-800">No matching fish found</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                Try clearing your search or filters to see our full aquarium collection.
            </p>
            <button id="resetFiltersBtn" type="button" class="mt-4 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-600 transition">
                Reset All Filters
            </button>
        </div>

    </section>

    <!-- Custom Request Banner (Great for conversions in Goa) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-gradient-to-r from-sky-600 via-sky-700 to-cyan-600 rounded-3xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="relative z-10 max-w-xl">
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-bold mb-3 tracking-wide backdrop-blur-sm">
                    ✨ Looking for a Special Breed?
                </span>
                <h3 class="text-2xl sm:text-3xl font-black tracking-tight leading-snug">
                    Can't find the exact fish you want?
                </h3>
                <p class="text-sky-100 text-sm mt-2 leading-relaxed">
                    We frequently source rare cichlids, exotic shrimps, fancy bettas, and planted tank varieties. Reach out directly and we will arrange it for you.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto relative z-10">
                <a href="https://wa.me/918806048011?text=Hello%20Aqua%20Galaxy,%20I%20am%20looking%20for%20a%20specific%20fish%20species:" 
                   target="_blank" rel="noopener"
                   class="bg-white text-slate-900 hover:bg-sky-50 px-6 py-3.5 rounded-2xl text-xs font-bold text-center shadow-lg transition">
                    Ask on WhatsApp
                </a>
                <a href="tel:+918806048011"
                   class="bg-sky-900/40 hover:bg-sky-900/60 border border-white/20 text-white px-6 py-3.5 rounded-2xl text-xs font-bold text-center transition">
                    Call Benton Monteiro
                </a>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("fishSearch");
    const clearBtn = document.getElementById("clearSearch");
    const cards = document.querySelectorAll(".fish-card");
    const catButtons = document.querySelectorAll(".cat-filter-btn");
    const careButtons = document.querySelectorAll(".care-filter-btn");
    const noResults = document.getElementById("noResults");
    const resultsCount = document.getElementById("resultsCount");
    const resetBtn = document.getElementById("resetFiltersBtn");

    let currentCat = "all";
    let currentCare = "all";
    let searchQuery = "";

    function filterCards() {
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name || "";
            const category = card.dataset.category || "";
            const care = card.dataset.care || "";

            const matchesSearch = !searchQuery || name.includes(searchQuery) || category.includes(searchQuery);
            const matchesCat = currentCat === "all" || category.includes(currentCat);
            const matchesCare = currentCare === "all" || care === currentCare;

            if (matchesSearch && matchesCat && matchesCare) {
                card.style.display = "flex";
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        });

        if (resultsCount) {
            resultsCount.textContent = `Showing ${visibleCount} varieties`;
        }

        if (noResults) {
            noResults.classList.toggle("hidden", visibleCount > 0);
        }
    }

    if (searchInput) {
        searchInput.addEventListener("input", function (e) {
            searchQuery = e.target.value.toLowerCase().trim();
            if (clearBtn) clearBtn.classList.toggle("hidden", searchQuery === "");
            filterCards();
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", function () {
            searchInput.value = "";
            searchQuery = "";
            clearBtn.classList.add("hidden");
            filterCards();
            searchInput.focus();
        });
    }

    catButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            catButtons.forEach(b => {
                b.classList.remove("active-cat", "bg-sky-500", "text-white");
                b.classList.add("bg-slate-100", "text-slate-700");
            });
            this.classList.add("active-cat", "bg-sky-500", "text-white");
            this.classList.remove("bg-slate-100", "text-slate-700");

            currentCat = this.dataset.cat;
            filterCards();
        });
    });

    careButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            careButtons.forEach(b => {
                b.classList.remove("active-filter", "bg-slate-800", "text-white");
                b.classList.add("bg-slate-100", "text-slate-700");
            });
            this.classList.add("active-filter", "bg-slate-800", "text-white");
            this.classList.remove("bg-slate-100", "text-slate-700");

            currentCare = this.dataset.care;
            filterCards();
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            if (searchInput) {
                searchInput.value = "";
                searchQuery = "";
            }
            if (clearBtn) clearBtn.classList.add("hidden");

            currentCat = "all";
            currentCare = "all";

            catButtons.forEach((b, idx) => {
                b.classList.toggle("active-cat", idx === 0);
                b.classList.toggle("bg-sky-500", idx === 0);
                b.classList.toggle("text-white", idx === 0);
                b.classList.toggle("bg-slate-100", idx !== 0);
                b.classList.toggle("text-slate-700", idx !== 0);
            });

            careButtons.forEach((b, idx) => {
                b.classList.toggle("active-filter", idx === 0);
                b.classList.toggle("bg-slate-800", idx === 0);
                b.classList.toggle("text-white", idx === 0);
                b.classList.toggle("bg-slate-100", idx !== 0);
                b.classList.toggle("text-slate-700", idx !== 0);
            });

            filterCards();
        });
    }
});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
