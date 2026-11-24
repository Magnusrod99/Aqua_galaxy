<?php
$pageTitle = "Photo Gallery | Aqua Galaxy";

require_once __DIR__ . "/config/app.php";
require_once __DIR__ . "/config/database.php";

$galleryItems = [];

if (isset($pdo) && $pdo !== null) {
    try {
        $stmt = $pdo->query("SELECT * FROM gallery WHERE status = 1 ORDER BY id DESC");
        $galleryItems = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Gallery query error: " . $e->getMessage());
    }
}

// Curated high quality gallery items if database table has no images yet
if (empty($galleryItems)) {
    $galleryItems = [
        [
            'id' => 1,
            'title' => 'Planted Aquascape with Cardinal Tetras',
            'category' => 'aquarium',
            'category_label' => 'Aquarium Life',
            'description' => 'Lush green planted aquarium featuring natural driftwood and a vibrant school of neon and cardinal tetras.',
            'image' => null,
            'icon' => '🌿'
        ],
        [
            'id' => 2,
            'title' => 'Custom Hand-Embroidered Saree Blouse',
            'category' => 'tailoring',
            'category_label' => 'Ladies Tailoring',
            'description' => 'Intricate thread work and custom piping on silk fabric with comfortable padded lining.',
            'image' => null,
            'icon' => '🧵'
        ],
        [
            'id' => 3,
            'title' => 'Red & White Oranda Goldfish Tank',
            'category' => 'aquarium',
            'category_label' => 'Aquarium Life',
            'description' => 'Healthy, active fancy goldfish enjoying a crystal clear water environment with biological canister filtration.',
            'image' => null,
            'icon' => '🐠'
        ],
        [
            'id' => 4,
            'title' => 'Aqua Galaxy Store Display in Thivim',
            'category' => 'shop',
            'category_label' => 'Our Shop',
            'description' => 'Our aquarium racks and fish accessories counter ready to welcome local hobbyists in Thivim, North Goa.',
            'image' => null,
            'icon' => '🏪'
        ],
        [
            'id' => 5,
            'title' => 'Festive Anarkali Suit with Gota Patti',
            'category' => 'tailoring',
            'category_label' => 'Ladies Tailoring',
            'description' => 'Flared silhouette with matching churidar and dupatta edge finishing, tailored for a Goa wedding reception.',
            'image' => null,
            'icon' => '👗'
        ],
        [
            'id' => 6,
            'title' => 'Aquarium Fish Food & Accessories Shelves',
            'category' => 'shop',
            'category_label' => 'Our Shop',
            'description' => 'Stocked shelves of internal filters, air pumps, LED lights, Hikari feeds, and water conditioners.',
            'image' => null,
            'icon' => '📦'
        ]
    ];
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="pt-24 sm:pt-28 min-h-screen">

    <!-- Hero Header -->
    <section class="bg-gradient-to-b from-slate-900 via-sky-950 to-slate-900 text-white py-12 sm:py-16 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-sky-500/20 text-cyan-300 text-xs font-semibold tracking-wide border border-sky-400/20 mb-4 backdrop-blur-md">
                <span>📸</span> Visual Showcase
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                Aqua Galaxy <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-cyan-400">Gallery</span>
            </h1>
            <p class="mt-3 text-slate-300 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Take a look at our ornamental fish collections, aquarium setups, and bespoke ladies' tailoring creations in Thivim, Goa.
            </p>
        </div>
    </section>

    <!-- Filter Buttons -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80 p-3 sm:p-4 flex items-center justify-center gap-2 overflow-x-auto scrollbar-none">
            <button type="button" class="gallery-cat-btn active-g-cat px-4 py-2 rounded-xl text-xs font-bold bg-sky-500 text-white transition whitespace-nowrap" data-gcat="all">
                All Photos
            </button>
            <button type="button" class="gallery-cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap" data-gcat="aquarium">
                🐠 Aquarium &amp; Fish
            </button>
            <button type="button" class="gallery-cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap" data-gcat="tailoring">
                ✂️ Ladies Tailoring
            </button>
            <button type="button" class="gallery-cat-btn px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition whitespace-nowrap" data-gcat="shop">
                🏪 Thivim Store
            </button>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div id="galleryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($galleryItems as $item): 
                $category = strtolower($item['category'] ?? 'other');
                $categoryLabel = $item['category_label'] ?? ucfirst($category);
                $icon = $item['icon'] ?? '📸';
            ?>
                <article 
                    class="gallery-card bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col cursor-pointer card-hover"
                    data-gcategory="<?= htmlspecialchars($category) ?>"
                    data-title="<?= htmlspecialchars($item['title']) ?>"
                    data-desc="<?= htmlspecialchars($item['description'] ?? '') ?>"
                    data-cat="<?= htmlspecialchars($categoryLabel) ?>"
                >
                    <!-- Visual Area -->
                    <div class="h-56 bg-gradient-to-tr from-slate-900 via-sky-950 to-slate-800 relative flex items-center justify-center overflow-hidden group">
                        
                        <?php if (!empty($item['image']) && file_exists(__DIR__ . '/uploads/gallery/' . $item['image'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/gallery/<?= htmlspecialchars($item['image']) ?>" 
                                 alt="<?= htmlspecialchars($item['title']) ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center p-6 text-center select-none">
                                <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-3xl shadow-inner backdrop-blur-sm group-hover:scale-110 transition">
                                    <?= $icon ?>
                                </div>
                                <span class="text-xs text-sky-200/70 font-medium mt-2">Tap to view full details</span>
                            </div>
                        <?php endif; ?>

                        <span class="absolute top-3 left-3 text-[11px] font-bold px-2.5 py-1 rounded-full bg-black/60 text-white backdrop-blur-md">
                            <?= htmlspecialchars($categoryLabel) ?>
                        </span>

                        <span class="absolute bottom-3 right-3 text-xs bg-white/90 text-slate-800 p-2 rounded-xl shadow-md opacity-90 group-hover:opacity-100 transition">
                            🔍
                        </span>
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-extrabold text-base sm:text-lg text-slate-900 leading-snug">
                                <?= htmlspecialchars($item['title']) ?>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed line-clamp-2">
                                <?= htmlspecialchars($item['description'] ?: 'Aqua Galaxy creation and products.') ?>
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-sky-600 font-bold">
                            <span>View details</span>
                            <span>&rarr;</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<!-- Lightbox Modal for Phones & Desktop -->
<div id="galleryModal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
        
        <button id="closeModal" type="button" class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center text-sm font-bold transition">
            ✕
        </button>

        <div class="h-60 bg-gradient-to-tr from-slate-900 via-sky-950 to-slate-800 flex items-center justify-center text-center p-6 text-white relative">
            <div id="modalIcon" class="text-5xl">📸</div>
            <span id="modalCategory" class="absolute top-4 left-4 text-xs font-bold px-3 py-1 rounded-full bg-white/20 text-white backdrop-blur-md">
                Category
            </span>
        </div>

        <div class="p-6">
            <h3 id="modalTitle" class="text-xl font-black text-slate-900 tracking-tight"></h3>
            <p id="modalDesc" class="text-sm text-slate-600 mt-3 leading-relaxed"></p>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                <a id="modalWhatsApp" href="https://wa.me/918806048011" target="_blank" rel="noopener"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center gap-2 transition">
                    <span>Ask About This on WhatsApp</span>
                    <span>💬</span>
                </a>
                <button id="modalDoneBtn" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                    Close
                </button>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const catButtons = document.querySelectorAll(".gallery-cat-btn");
    const cards = document.querySelectorAll(".gallery-card");
    const modal = document.getElementById("galleryModal");
    const closeModal = document.getElementById("closeModal");
    const modalDoneBtn = document.getElementById("modalDoneBtn");
    const modalTitle = document.getElementById("modalTitle");
    const modalDesc = document.getElementById("modalDesc");
    const modalCategory = document.getElementById("modalCategory");
    const modalWhatsApp = document.getElementById("modalWhatsApp");

    catButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            catButtons.forEach(b => {
                b.classList.remove("active-g-cat", "bg-sky-500", "text-white");
                b.classList.add("bg-slate-100", "text-slate-700");
            });

            this.classList.add("active-g-cat", "bg-sky-500", "text-white");
            this.classList.remove("bg-slate-100", "text-slate-700");

            const selectedCat = this.dataset.gcat;

            cards.forEach(card => {
                const cardCat = card.dataset.gcategory || "";
                if (selectedCat === "all" || cardCat === selectedCat) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });

    cards.forEach(card => {
        card.addEventListener("click", function () {
            const title = this.dataset.title;
            const desc = this.dataset.desc;
            const cat = this.dataset.cat;

            modalTitle.textContent = title;
            modalDesc.textContent = desc;
            modalCategory.textContent = cat;

            const msg = encodeURIComponent("Hello Aqua Galaxy, I saw this in your gallery: '" + title + "'. I'd like more details!");
            modalWhatsApp.href = "https://wa.me/918806048011?text=" + msg;

            modal.classList.remove("hidden");
            document.body.style.overflow = "hidden";
        });
    });

    function hideModal() {
        modal.classList.add("hidden");
        document.body.style.overflow = "";
    }

    if (closeModal) closeModal.addEventListener("click", hideModal);
    if (modalDoneBtn) modalDoneBtn.addEventListener("click", hideModal);
    if (modal) {
        modal.addEventListener("click", function (e) {
            if (e.target === modal) hideModal();
        });
    }
});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
