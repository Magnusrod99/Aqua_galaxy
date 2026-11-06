
<?php

session_start();

require_once __DIR__ . "/../config/app.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: " . BASE_URL . "/admin/login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

$pageTitle = "Admin Dashboard | Aqua Galaxy";

// Get live counts from the existing database tables
$counts = [
    "fish" => 0,
    "products" => 0,
    "aquarium_services" => 0,
    "tailoring_services" => 0,
    "enquiries" => 0,
];

try {
    $counts["fish"] = (int) $pdo
        ->query("SELECT COUNT(*) FROM fish")
        ->fetchColumn();

    $counts["products"] = (int) $pdo
        ->query("SELECT COUNT(*) FROM aquarium_products")
        ->fetchColumn();

    $counts["aquarium_services"] = (int) $pdo
        ->query("SELECT COUNT(*) FROM aquarium_services")
        ->fetchColumn();

    $counts["tailoring_services"] = (int) $pdo
        ->query("SELECT COUNT(*) FROM tailoring_services")
        ->fetchColumn();

    $counts["enquiries"] = (int) $pdo
        ->query("SELECT COUNT(*) FROM enquiries WHERE status = 'new'")
        ->fetchColumn();

} catch (PDOException $e) {
    error_log("Dashboard count query failed: " . $e->getMessage());
}

require_once __DIR__ . "/../includes/header.php";

?>

<div class="min-h-screen bg-slate-50">

    <!-- Top Bar -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">

            <a href="<?= BASE_URL ?>/admin/dashboard.php"
               class="flex items-center gap-3 min-w-0">

                <div class="w-10 h-10 shrink-0 rounded-full bg-sky-100 border-2 border-sky-300 flex items-center justify-center text-2xl">
                    🐟
                </div>

                <div class="min-w-0">
                    <h1 class="font-bold text-lg sm:text-xl text-slate-900">
                        Aqua <span class="text-sky-500">Galaxy</span>
                    </h1>
                    <p class="text-xs text-slate-500">
                        Administration Panel
                    </p>
                </div>
            </a>

            <div class="flex items-center gap-3">

                <span class="hidden sm:block text-sm text-slate-600">
                    <?= htmlspecialchars(
                        $_SESSION["admin_name"] ?? "Admin",
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </span>

                <form action="<?= BASE_URL ?>/admin/logout.php" method="POST">
                    <button
                        type="submit"
                        class="bg-red-50 hover:bg-red-100 text-red-600 px-3 sm:px-4 py-2 rounded-lg text-sm font-semibold transition"
                    >
                        Log Out
                    </button>
                </form>

            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Welcome Section -->
        <section class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">
                Dashboard Overview
            </h2>

            <p class="mt-2 text-slate-500">
                Welcome back,
                <?= htmlspecialchars(
                    $_SESSION["admin_name"] ?? "Admin",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>!
                Here's your Aqua Galaxy website summary.
            </p>
        </section>

        <!-- Summary Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

            <!-- Fish Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Ornamental Fish</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            <?= $counts["fish"] ?>
                        </p>
                    </div>

                    <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center text-2xl">
                        🐠
                    </div>
                </div>

                <p class="text-xs text-slate-500 mt-4">
                    Total fish records
                </p>
            </div>

            <!-- Product Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Aquarium Products</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            <?= $counts["products"] ?>
                        </p>
                    </div>

                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl">
                        🪸
                    </div>
                </div>

                <p class="text-xs text-slate-500 mt-4">
                    Total product records
                </p>
            </div>

            <!-- Aquarium Services Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Aquarium Services</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            <?= $counts["aquarium_services"] ?>
                        </p>
                    </div>

                    <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center text-2xl">
                        🫧
                    </div>
                </div>

                <p class="text-xs text-slate-500 mt-4">
                    Total service records
                </p>
            </div>

            <!-- Tailoring Services Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Tailoring Services</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            <?= $counts["tailoring_services"] ?>
                        </p>
                    </div>

                    <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center text-2xl">
                        ✂️
                    </div>
                </div>

                <p class="text-xs text-slate-500 mt-4">
                    Total service records
                </p>
            </div>

            <!-- New Enquiries Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">New Enquiries</p>
                        <p class="text-3xl font-bold text-slate-900 mt-2">
                            <?= $counts["enquiries"] ?>
                        </p>
                    </div>

                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">
                        📩
                    </div>
                </div>

                <p class="text-xs text-slate-500 mt-4">
                    Awaiting your attention
                </p>
            </div>

        </section>

        <!-- Website Management -->
        <section class="mt-10">

            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-900">
                    Website Management
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Manage your Aqua Galaxy website from one place.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                <!-- Fish Management: Connected -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-sky-300 transition">
                    <div class="text-2xl mb-3">🐠</div>

                    <h3 class="font-bold text-slate-900">
                        Fish Management
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Manage fish listings and fish categories.
                    </p>

                    <a
                        href="<?= BASE_URL ?>/admin/fish.php"
                        class="inline-flex items-center gap-2 mt-5 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition"
                    >
                        Manage Fish
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <!-- Product Management -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="text-2xl mb-3">🪸</div>

                    <h3 class="font-bold text-slate-900">
                        Product Management
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Manage aquarium products and categories.
                    </p>

                    <span class="inline-block mt-5 text-sm text-slate-400">
                        Coming next
                    </span>
                </div>

                <!-- Aquarium Services -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="text-2xl mb-3">🫧</div>

                    <h3 class="font-bold text-slate-900">
                        Aquarium Services
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Manage aquarium cleaning and other services.
                    </p>

                    <span class="inline-block mt-5 text-sm text-slate-400">
                        Coming next
                    </span>
                </div>

                <!-- Tailoring Services -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="text-2xl mb-3">✂️</div>

                    <h3 class="font-bold text-slate-900">
                        Tailoring Services
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Manage tailoring services and descriptions.
                    </p>

                    <span class="inline-block mt-5 text-sm text-slate-400">
                        Coming next
                    </span>
                </div>

                <!-- Gallery -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="text-2xl mb-3">🖼️</div>

                    <h3 class="font-bold text-slate-900">
                        Gallery
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Organize aquarium and tailoring photos.
                    </p>

                    <span class="inline-block mt-5 text-sm text-slate-400">
                        Coming next
                    </span>
                </div>

                <!-- Customer Enquiries -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="text-2xl mb-3">📩</div>

                    <h3 class="font-bold text-slate-900">
                        Customer Enquiries
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Review messages submitted by customers.
                    </p>

                    <span class="inline-block mt-5 text-sm text-slate-400">
                        Coming next
                    </span>
                </div>

            </div>
        </section>

    </main>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
