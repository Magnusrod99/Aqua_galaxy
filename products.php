<?php
$pageTitle = "Aquarium Products & Essentials | Aqua Galaxy";

require_once __DIR__ . "/config/app.php";
require_once __DIR__ . "/config/database.php";

$categories = [];
$productList = [];

if (isset($pdo) && $pdo !== null) {
    try {
        $catStmt = $pdo->query("SELECT id, name, slug FROM aquarium_product_categories WHERE status = 1 ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        $prodStmt = $pdo->query("
            SELECT p.*, c.name AS category_name 
            FROM aquarium_products p
            LEFT JOIN aquarium_product_categories c ON p.category_id = c.id
            WHERE p.status = 1
            ORDER BY p.id DESC
        ");
        $productList = $prodStmt->fetchAll();
    } catch (Exception $e) {
        error_log("Products query error: " . $e->getMessage());
    }
}

// Curated high quality product catalog for immediate display
if (empty($productList)) {
    $categories = [
        ['id' => 1, 'name' => 'Fish Food & Nutrition', 'slug' => 'food'],
        ['id' => 2, 'name' => 'Filters & Pumps', 'slug' => 'filters'],
        ['id' => 3, 'name' => 'Aquarium Lighting', 'slug' => 'lighting'],
        ['id' => 4, 'name' => 'Water Care & Conditioners', 'slug' => 'water-care'],
        ['id' => 5, 'name' => 'Decor & Plants', 'slug' => 'decor'],
    ];

    $productList = [
        [
            'id' => 1,
            'name' => 'Hikari & Taiyo Premium Color Enhancing Pellets',
            'category_name' => 'Fish Food & Nutrition',
            'description' => 'Rich in spirulina, astaxanthin, and essential vitamins. Boosts fish immunity and brings out natural vibrant reds and golds.',
            'price' => 180,
            'image' => null,
            'badge' => 'Daily Essential',
            'specs' => 'For Goldfish, Cichlids & Community • 100g / 250g packs'
        ],
        [
            'id' => 2,
            'name' => 'Sobo Submersible Power Internal Filter (600 L/H)',
            'category_name' => 'Filters & Pumps',
            'description' => 'Dual mechanical and biological sponge filtration with silent water circulation and oxygen nozzle attachment.',
            'price' => 450,
            'image' => null,
            'badge' => 'Energy Efficient',
            'specs' => 'Suitable for 1.5ft – 2.5ft Aquariums • Silent Motor'
        ],
        [
            'id' => 3,
            'name' => 'Slim Clip-On Full Spectrum LED Aquarium Light',
            'category_name' => 'Aquarium Lighting',
            'description' => 'Bright white, blue, and plant-growth boosting LEDs. Highlights the natural luster of fish scales and supports plant photosynthesis.',
            'price' => 590,
            'image' => null,
            'badge' => 'Plant Ready',
            'specs' => 'Adjustable bracket • Fits rimmed and rimless tanks'
        ],
        [
            'id' => 4,
            'name' => 'Ultra-Quiet Dual Nozzle Air Pump & Aerator',
            'category_name' => 'Filters & Pumps',
            'description' => 'High oxygen output with low power consumption and anti-vibration rubber feet for whisper-silent night operation.',
            'price' => 380,
            'image' => null,
            'badge' => 'Top Value',
            'specs' => 'Dual Outlets • High pressure airflow'
        ],
        [
            'id' => 5,
            'name' => 'Instant Anti-Chlorine & Water Conditioner (250ml)',
            'category_name' => 'Water Care & Conditioners',
            'description' => 'Instantly neutralizes toxic tap water chlorine, chloramines, and heavy metals. Protects fish slime coats during water changes.',
            'price' => 220,
            'image' => null,
            'badge' => 'Must Have',
            'specs' => 'Fast Acting • Safe for all freshwater & shrimp'
        ],
        [
            'id' => 6,
            'name' => 'Hardy Aquatic Live Plants (Anubias & Java Fern)',
            'category_name' => 'Decor & Plants',
            'description' => 'Easy-to-maintain live plants that thrive in low-light setups without needing CO2. Natural shelter for baby fish and shrimp.',
            'price' => 150,
            'image' => null,
            'badge' => 'Low Light',
            'specs' => 'Mounted on driftwood or lava rock options available'
        ]
    ];
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="pt-24 sm:pt-28 min-h-screen">

    <!-- Hero Header -->
    <section class="bg-gradient-to-b from-slate-900 via-sky-950 to-slate-900 text-white py-12 sm:py-16 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-sky-500/20 text-cyan-300 text-xs font-semibold tracking-wide border border-sky-400/20 mb-4 backdrop-blur-md">
                <span>🪸</span> Complete Aquarium Gear in Goa
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                Aquarium <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-cyan-400">Products &amp; Food</span>
            </h1>
            <p class="mt-3 text-slate-300 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Everything you need to set up, maintain, and nourish your aquatic paradise — from trusted feeds and filters to LED lights.
            </p>
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
                        id="productSearch" 
                        placeholder="Search products (e.g. Filter, Hikari, LED, Food, Pump)..."
                        class="w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                    >
                    <button id="clearProductSearch" type="button" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 text-sm font-bold">
                        ✕
                    </button>
                </div>

                <div class="text-xs text-slate-500 font-semibold flex items-center gap-2 justify-end">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>All products available in Thivim shop</span>
                </div>

            </div>

            <!-- Category Pills -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2 overflow-x-auto pb-1 text-sm scrollbar-none">
                <button type="button" class="prod-cat-btn active-prod-cat px-3.5 py-1.5 rounded-full text-xs font-bold bg-sky-500 text-white shadow-sm transition whitespace-nowrap" data-cat="all">
                    All Products
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button type="button" class="prod-cat-btn px-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition whitespace-nowrap" data-cat="<?= htmlspecialchars(strtolower($cat['name'])) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Product Grid Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Featured Essentials
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5" id="prodResultsCount">
                    Showing <?= count($productList) ?> items
                </p>
            </div>

            <a href="https://wa.me/918806048011?text=Hi%20Aqua%20Galaxy,%20do%20you%20have%20custom%20glass%20tanks%20available?" 
               target="_blank" rel="noopener"
               class="text-xs sm:text-sm font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1.5 bg-sky-50 px-3 py-2 rounded-xl border border-sky-100 transition">
                <span>Custom Tank Sizing</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($productList as $product): 
                $priceDisplay = !empty($product['price']) ? '₹' . number_format($product['price']) : 'Price on Request';
                $categoryName = $product['category_name'] ?? 'Aquarium Supplies';
                $waMessage = rawurlencode("Hello Aqua Galaxy, I want to inquire about purchasing: " . $product['name'] . " (" . $priceDisplay . "). Is it currently available at your Thivim store?");
            ?>
                <article 
                    class="product-card bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col card-hover"
                    data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>"
                    data-category="<?= htmlspecialchars(strtolower($categoryName)) ?>"
                >
                    <!-- Visual Header -->
                    <div class="h-44 bg-gradient-to-tr from-slate-800 via-slate-900 to-sky-900 relative flex items-center justify-center p-6 text-center overflow-hidden">
                        
                        <?php if (!empty($product['image']) && file_exists(__DIR__ . '/uploads/products/' . $product['image'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/products/<?= htmlspecialchars($product['image']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>" 
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-3xl shadow-inner backdrop-blur-sm">
                                📦
                            </div>
                        <?php endif; ?>

                        <!-- Category Pill -->
                        <span class="absolute top-3 left-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/90 text-slate-800 shadow-sm backdrop-blur-md">
                            <?= htmlspecialchars($categoryName) ?>
                        </span>

                        <span class="absolute top-3 right-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-500/90 text-white shadow-sm backdrop-blur-md">
                            ✓ In Stock
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight leading-snug">
                                <?= htmlspecialchars($product['name']) ?>
                            </h3>

                            <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
                                <?= htmlspecialchars($product['description'] ?: 'High quality tested aquarium product to keep your aquatic setup thriving.') ?>
                            </p>

                            <?php if (!empty($product['specs'])): ?>
                                <div class="mt-3 text-[11px] text-sky-800 bg-sky-50 p-2.5 rounded-lg border border-sky-100 flex items-center gap-1.5 font-medium">
                                    <span>⚙️</span>
                                    <span><?= htmlspecialchars($product['specs']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Price & Action -->
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Price</span>
                                <span class="text-lg font-black text-slate-900 tracking-tight">
                                    <?= $priceDisplay ?>
                                </span>
                            </div>

                            <a href="https://wa.me/918806048011?text=<?= $waMessage ?>"
                               target="_blank" rel="noopener"
                               class="bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition transform active:scale-95">
                                <span>Order / Inquire</span>
                                <span class="text-emerald-200">💬</span>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- No Results -->
        <div id="noProdResults" class="hidden text-center py-16 bg-white rounded-3xl border border-slate-200 my-8">
            <div class="text-5xl mb-3">📦</div>
            <h3 class="text-lg font-bold text-slate-800">No matching products found</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                We carry a wide variety of accessories at our Thivim store that might not be listed yet.
            </p>
            <button id="resetProdFiltersBtn" type="button" class="mt-4 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-600 transition">
                Reset Filters
            </button>
        </div>

    </section>

    <!-- Custom Tanks & Accessories Consultation Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-gradient-to-r from-slate-900 to-sky-950 rounded-3xl p-6 sm:p-10 text-white border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="inline-block px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 text-xs font-bold mb-3 tracking-wide">
                    🛠️ Custom Tank Setup
                </span>
                <h3 class="text-2xl sm:text-3xl font-black tracking-tight leading-snug">
                    Need a Custom Glass Tank or Full Setup?
                </h3>
                <p class="text-slate-300 text-sm mt-2 leading-relaxed">
                    We build customized glass aquariums in any dimensions (1ft to 6ft+), with rimless ultra-clear glass, cabinet stands, and complete filtration setups.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="https://wa.me/918806048011?text=Hello%20Aqua%20Galaxy,%20I%20would%20like%20a%20quotation%20for%20a%20custom%20aquarium%20tank:"
                   target="_blank" rel="noopener"
                   class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3.5 rounded-2xl text-xs font-bold text-center shadow-lg transition">
                    Get Custom Quote
                </a>
                <a href="tel:+918806048011"
                   class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-6 py-3.5 rounded-2xl text-xs font-bold text-center transition">
                    Call Store
                </a>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("productSearch");
    const clearBtn = document.getElementById("clearProductSearch");
    const cards = document.querySelectorAll(".product-card");
    const catButtons = document.querySelectorAll(".prod-cat-btn");
    const noResults = document.getElementById("noProdResults");
    const resultsCount = document.getElementById("prodResultsCount");
    const resetBtn = document.getElementById("resetProdFiltersBtn");

    let currentCat = "all";
    let searchQuery = "";

    function filterProducts() {
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name || "";
            const category = card.dataset.category || "";

            const matchesSearch = !searchQuery || name.includes(searchQuery) || category.includes(searchQuery);
            const matchesCat = currentCat === "all" || category.includes(currentCat);

            if (matchesSearch && matchesCat) {
                card.style.display = "flex";
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        });

        if (resultsCount) {
            resultsCount.textContent = `Showing ${visibleCount} items`;
        }

        if (noResults) {
            noResults.classList.toggle("hidden", visibleCount > 0);
        }
    }

    if (searchInput) {
        searchInput.addEventListener("input", function (e) {
            searchQuery = e.target.value.toLowerCase().trim();
            if (clearBtn) clearBtn.classList.toggle("hidden", searchQuery === "");
            filterProducts();
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener("click", function () {
            searchInput.value = "";
            searchQuery = "";
            clearBtn.classList.add("hidden");
            filterProducts();
            searchInput.focus();
        });
    }

    catButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            catButtons.forEach(b => {
                b.classList.remove("active-prod-cat", "bg-sky-500", "text-white");
                b.classList.add("bg-slate-100", "text-slate-700");
            });
            this.classList.add("active-prod-cat", "bg-sky-500", "text-white");
            this.classList.remove("bg-slate-100", "text-slate-700");

            currentCat = this.dataset.cat;
            filterProducts();
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

            catButtons.forEach((b, idx) => {
                b.classList.toggle("active-prod-cat", idx === 0);
                b.classList.toggle("bg-sky-500", idx === 0);
                b.classList.toggle("text-white", idx === 0);
                b.classList.toggle("bg-slate-100", idx !== 0);
                b.classList.toggle("text-slate-700", idx !== 0);
            });

            filterProducts();
        });
    }
});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
