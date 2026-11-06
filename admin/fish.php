
<?php
session_start();

require_once __DIR__ . "/../config/app.php";

// Check admin authentication
if (!isset($_SESSION["admin_id"])) {
    header("Location: " . BASE_URL . "/admin/login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

// Generate a CSRF token for secure delete requests
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$pageTitle = "Fish Management | Aqua Galaxy";

$categories = [];
$fishListings = [];
$error = "";
$success = "";

// Generate a URL-friendly slug
function createFishSlug(string $name): string
{
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

    return trim($slug, '-');
}

// Handle messages after redirects
if (isset($_GET["created"]) && $_GET["created"] === "1") {
    $success = "Fish listing saved successfully!";
}

if (isset($_GET["deleted"]) && $_GET["deleted"] === "1") {
    $success = "Fish listing deleted successfully!";
}

if (isset($_GET["delete_error"])) {
    $deleteErrors = [
        "invalid" => "Invalid fish selected for deletion.",
        "notfound" => "The selected fish could not be found.",
        "security" => "Security verification failed. Please try again.",
        "failed" => "Unable to delete the fish. It may be linked to other records."
    ];

    $error = $deleteErrors[$_GET["delete_error"]]
        ?? "An error occurred while deleting the fish.";
}

// Save a new fish listing
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");

    $categoryId = filter_var(
        $_POST["category_id"] ?? null,
        FILTER_VALIDATE_INT
    );

    $description = trim($_POST["description"] ?? "");
    $pricingType = $_POST["pricing_type"] ?? "fixed";
    $priceMin = $_POST["price_min"] ?? "";
    $priceMax = $_POST["price_max"] ?? "";
    $careLevel = $_POST["care_level"] ?? "";
    $tankSize = trim($_POST["tank_size"] ?? "");
    $temperature = trim($_POST["temperature"] ?? "");
    $food = trim($_POST["food"] ?? "");

    if ($name === "" || strlen($name) > 150) {
        $error = "Enter a fish name of up to 150 characters.";

    } elseif (!$categoryId || $categoryId < 1) {
        $error = "Please select a valid fish category.";

    } elseif (strlen($description) > 5000) {
        $error = "Description must be 5000 characters or fewer.";

    } elseif (!in_array($pricingType, ["fixed", "range"], true)) {
        $error = "Select a valid pricing type.";

    } elseif (
        !is_numeric($priceMin) ||
        !is_finite((float) $priceMin) ||
        (float) $priceMin < 0 ||
        (float) $priceMin > 9999999999999.99
    ) {
        $error = "Enter a valid non-negative price.";

    } elseif (
        $pricingType === "range" &&
        (
            $priceMax === "" ||
            !is_numeric($priceMax) ||
            !is_finite((float) $priceMax) ||
            (float) $priceMax < (float) $priceMin ||
            (float) $priceMax > 9999999999999.99
        )
    ) {
        $error = "Maximum price must be at least the minimum price.";

    } elseif (!in_array($careLevel, ["easy", "medium", "hard"], true)) {
        $error = "Please select a valid care level.";

    } elseif (
        strlen($tankSize) > 100 ||
        strlen($temperature) > 100 ||
        strlen($food) > 255
    ) {
        $error = "One or more care details exceed the allowed length.";

    } else {

        $slug = createFishSlug($name);

        if ($slug === "") {
            $error = "Use a fish name containing English letters or numbers.";

        } else {

            $min = round((float) $priceMin, 2);

            $max = $pricingType === "fixed"
                ? $min
                : round((float) $priceMax, 2);

            try {

                // Verify the selected category is active
                $categoryStmt = $pdo->prepare(
                    "SELECT id
                     FROM fish_categories
                     WHERE id = :id AND status = 1"
                );

                $categoryStmt->execute([
                    "id" => $categoryId
                ]);

                if (!$categoryStmt->fetch()) {
                    $error = "The selected category is unavailable.";

                } else {

                    $stmt = $pdo->prepare(
                        "INSERT INTO fish (
                            category_id,
                            name,
                            slug,
                            description,
                            price_min,
                            price_max,
                            care_level,
                            tank_size,
                            temperature,
                            food,
                            status
                        ) VALUES (
                            :category_id,
                            :name,
                            :slug,
                            :description,
                            :price_min,
                            :price_max,
                            :care_level,
                            :tank_size,
                            :temperature,
                            :food,
                            1
                        )"
                    );

                    $stmt->execute([
                        "category_id" => $categoryId,
                        "name" => $name,
                        "slug" => $slug,
                        "description" => $description !== ""
                            ? $description : null,
                        "price_min" => $min,
                        "price_max" => $max,
                        "care_level" => $careLevel,
                        "tank_size" => $tankSize !== ""
                            ? $tankSize : null,
                        "temperature" => $temperature !== ""
                            ? $temperature : null,
                        "food" => $food !== ""
                            ? $food : null
                    ]);

                    header(
                        "Location: " . BASE_URL .
                        "/admin/fish.php?created=1"
                    );
                    exit;
                }

            } catch (PDOException $e) {

                if ($e->getCode() === "23000") {
                    $error = "A fish with this URL slug already exists. Try a more specific name.";
                } else {
                    error_log("Fish creation failed: " . $e->getMessage());
                    $error = "Unable to save the fish. Please try again.";
                }
            }
        }
    }
}

// Load active categories
try {

    $stmt = $pdo->query(
        "SELECT id, name
         FROM fish_categories
         WHERE status = 1
         ORDER BY name ASC"
    );

    $categories = $stmt->fetchAll();

} catch (PDOException $e) {

    error_log("Fish category loading failed: " . $e->getMessage());
    $error = "Unable to load fish categories. Please try again.";
}

// Load all fish listings
try {

    $stmt = $pdo->query(
        "SELECT
            fish.id,
            fish.name,
            fish.slug,
            fish.description,
            fish.price_min,
            fish.price_max,
            fish.care_level,
            fish.tank_size,
            fish.temperature,
            fish.food,
            fish.status,
            fish_categories.name AS category_name
         FROM fish
         INNER JOIN fish_categories
            ON fish.category_id = fish_categories.id
         ORDER BY fish.id DESC"
    );

    $fishListings = $stmt->fetchAll();

} catch (PDOException $e) {

    error_log("Fish listing loading failed: " . $e->getMessage());
    $error = "Unable to load saved fish listings.";
}

// Format prices
function formatFishPrice($minimum, $maximum): string
{
    $min = (float) $minimum;
    $max = (float) $maximum;

    if ($min === $max) {
        return "₹" . number_format($min, 2);
    }

    return "₹" . number_format($min, 2)
        . " – ₹" . number_format($max, 2);
}

require_once __DIR__ . "/../includes/header.php";
?>

<main class="min-h-screen bg-slate-50 px-4 py-8 sm:px-8">
    <div class="max-w-7xl mx-auto">

        <a
            href="<?= BASE_URL ?>/admin/dashboard.php"
            class="text-sm text-sky-600 hover:text-sky-700"
        >
            &larr; Back to Dashboard
        </a>

        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-4">
            Fish Management
        </h1>

        <p class="text-slate-500 mt-2 mb-8">
            Manage your ornamental fish listings, images and prices.
        </p>

        <!-- Add Fish Form -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6 mb-8">

            <h2 class="text-lg font-bold text-slate-900 mb-6">
                Add New Fish
            </h2>

            <?php if ($error !== ""): ?>
                <div
                    role="alert"
                    class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700"
                >
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <?php if ($success !== ""): ?>
                <div
                    role="status"
                    class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700"
                >
                    <?= htmlspecialchars($success, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form
                method="POST"
                action="<?= BASE_URL ?>/admin/fish.php"
                class="grid grid-cols-1 md:grid-cols-2 gap-5"
            >

                <div>
                    <label for="fish_name" class="block text-sm font-medium text-slate-700 mb-2">
                        Fish Name
                    </label>

                    <input
                        type="text"
                        id="fish_name"
                        name="name"
                        maxlength="150"
                        placeholder="e.g. Red Oranda Goldfish"
                        required
                        value="<?= htmlspecialchars($_POST["name"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Fish Category
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                        <option value="">Select a category</option>

                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= (int) $category["id"] ?>"
                                <?= (string) ($_POST["category_id"] ?? "") === (string) $category["id"] ? "selected" : "" ?>
                            >
                                <?= htmlspecialchars($category["name"], ENT_QUOTES, "UTF-8") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if (empty($categories)): ?>
                        <p class="text-sm text-amber-600 mt-2">
                            Create an active fish category first.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        maxlength="5000"
                        placeholder="Describe the fish..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    ><?= htmlspecialchars($_POST["description"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>
                </div>

                <div>
                    <label for="pricing_type" class="block text-sm font-medium text-slate-700 mb-2">
                        Pricing Type
                    </label>

                    <select
                        id="pricing_type"
                        name="pricing_type"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                        <option value="fixed" <?= ($_POST["pricing_type"] ?? "fixed") === "fixed" ? "selected" : "" ?>>
                            Fixed Price
                        </option>

                        <option value="range" <?= ($_POST["pricing_type"] ?? "") === "range" ? "selected" : "" ?>>
                            Price Range
                        </option>
                    </select>
                </div>

                <div>
                    <label
                        for="price_min"
                        id="price_min_label"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Price (₹)
                    </label>

                    <input
                        type="number"
                        id="price_min"
                        name="price_min"
                        min="0"
                        step="0.01"
                        placeholder="e.g. 200"
                        required
                        value="<?= htmlspecialchars($_POST["price_min"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                </div>

                <div id="price_max_wrapper" class="hidden">
                    <label for="price_max" class="block text-sm font-medium text-slate-700 mb-2">
                        Maximum Price (₹)
                    </label>

                    <input
                        type="number"
                        id="price_max"
                        name="price_max"
                        min="0"
                        step="0.01"
                        placeholder="e.g. 500"
                        value="<?= htmlspecialchars($_POST["price_max"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                        disabled
                    >
                </div>

                <div>
                    <label for="care_level" class="block text-sm font-medium text-slate-700 mb-2">
                        Care Level
                    </label>

                    <select
                        id="care_level"
                        name="care_level"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                        <option value="">Select care level</option>
                        <option value="easy" <?= ($_POST["care_level"] ?? "") === "easy" ? "selected" : "" ?>>Easy</option>
                        <option value="medium" <?= ($_POST["care_level"] ?? "") === "medium" ? "selected" : "" ?>>Medium</option>
                        <option value="hard" <?= ($_POST["care_level"] ?? "") === "hard" ? "selected" : "" ?>>Hard</option>
                    </select>
                </div>

                <div>
                    <label for="tank_size" class="block text-sm font-medium text-slate-700 mb-2">
                        Recommended Tank Size
                    </label>

                    <input
                        type="text"
                        id="tank_size"
                        name="tank_size"
                        maxlength="100"
                        placeholder="e.g. 20 gallons"
                        value="<?= htmlspecialchars($_POST["tank_size"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                </div>

                <div>
                    <label for="temperature" class="block text-sm font-medium text-slate-700 mb-2">
                        Temperature
                    </label>

                    <input
                        type="text"
                        id="temperature"
                        name="temperature"
                        maxlength="100"
                        placeholder="e.g. 20–24°C"
                        value="<?= htmlspecialchars($_POST["temperature"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                </div>

                <div>
                    <label for="food" class="block text-sm font-medium text-slate-700 mb-2">
                        Food
                    </label>

                    <input
                        type="text"
                        id="food"
                        name="food"
                        maxlength="255"
                        placeholder="e.g. Pellets and flakes"
                        value="<?= htmlspecialchars($_POST["food"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    >
                </div>

                <div class="md:col-span-2 pt-2">
                    <button
                        type="submit"
                        class="w-full sm:w-auto rounded-lg bg-sky-500 px-6 py-3 font-semibold text-white hover:bg-sky-600 transition"
                    >
                        Save Fish
                    </button>
                </div>

            </form>
        </section>

        <!-- Saved Fish Listings -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        Saved Fish Listings
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Fish currently stored in your database.
                    </p>
                </div>

                <span class="bg-sky-100 text-sky-700 px-3 py-2 rounded-full text-sm font-semibold">
                    <?= count($fishListings) ?> total
                </span>
            </div>

            <?php if (empty($fishListings)): ?>

                <div class="p-8 text-center">
                    <div class="text-4xl mb-3">🐠</div>

                    <h3 class="font-semibold text-slate-900">
                        No fish listings yet
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Add your first fish using the form above.
                    </p>
                </div>

            <?php else: ?>

                <!-- Desktop table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left">

                        <thead class="bg-slate-50 text-sm text-slate-600">
                            <tr>
                                <th class="px-5 py-4">Fish</th>
                                <th class="px-5 py-4">Category</th>
                                <th class="px-5 py-4">Price</th>
                                <th class="px-5 py-4">Care Level</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            <?php foreach ($fishListings as $fish): ?>

                                <tr class="hover:bg-slate-50">

                                    <td class="px-5 py-4">
                                        <p class="font-medium text-slate-900">
                                            <?= htmlspecialchars($fish["name"], ENT_QUOTES, "UTF-8") ?>
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            <?= htmlspecialchars($fish["slug"], ENT_QUOTES, "UTF-8") ?>
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        <?= htmlspecialchars($fish["category_name"], ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td class="px-5 py-4 text-sm font-semibold text-slate-900">
                                        <?= htmlspecialchars(formatFishPrice($fish["price_min"], $fish["price_max"]), ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        <?= htmlspecialchars(ucfirst($fish["care_level"]), ENT_QUOTES, "UTF-8") ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?php if ((int) $fish["status"] === 1): ?>
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-semibold">
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Desktop actions -->
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-2">

                                            <a
                                                href="<?= BASE_URL ?>/admin/edit-fish.php?id=<?= (int) $fish["id"] ?>"
                                                class="inline-block rounded-lg bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-200 transition"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                method="POST"
                                                action="<?= BASE_URL ?>/admin/delete-fish.php"
                                                onsubmit="return confirm('Are you sure you want to delete this fish? This action cannot be undone.');"
                                            >
                                                <input
                                                    type="hidden"
                                                    name="fish_id"
                                                    value="<?= (int) $fish["id"] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-100 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-200 transition"
                                                >
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <!-- Mobile cards -->
                <div class="md:hidden divide-y divide-slate-100">

                    <?php foreach ($fishListings as $fish): ?>

                        <article class="p-5">

                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-slate-900">
                                        <?= htmlspecialchars($fish["name"], ENT_QUOTES, "UTF-8") ?>
                                    </h3>

                                    <p class="text-sm text-sky-700 mt-1">
                                        <?= htmlspecialchars($fish["category_name"], ENT_QUOTES, "UTF-8") ?>
                                    </p>
                                </div>

                                <?php if ((int) $fish["status"] === 1): ?>
                                    <span class="shrink-0 bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="shrink-0 bg-slate-100 text-slate-600 px-2 py-1 rounded-full text-xs font-semibold">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </div>

                            <p class="text-lg font-bold text-slate-900 mt-3">
                                <?= htmlspecialchars(formatFishPrice($fish["price_min"], $fish["price_max"]), ENT_QUOTES, "UTF-8") ?>
                            </p>

                            <p class="text-sm text-slate-600 mt-2">
                                Care level:
                                <?= htmlspecialchars(ucfirst($fish["care_level"]), ENT_QUOTES, "UTF-8") ?>
                            </p>

                            <?php if (!empty($fish["description"])): ?>
                                <p class="text-sm text-slate-600 mt-2">
                                    <?= htmlspecialchars($fish["description"], ENT_QUOTES, "UTF-8") ?>
                                </p>
                            <?php endif; ?>

                            <!-- Mobile actions -->
                            <div class="mt-4 flex flex-wrap gap-2">

                                <a
                                    href="<?= BASE_URL ?>/admin/edit-fish.php?id=<?= (int) $fish["id"] ?>"
                                    class="inline-flex items-center justify-center rounded-lg bg-sky-100 px-4 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-200 transition"
                                >
                                    Edit Fish
                                </a>

                                <form
                                    method="POST"
                                    action="<?= BASE_URL ?>/admin/delete-fish.php"
                                    onsubmit="return confirm('Are you sure you want to delete this fish? This action cannot be undone.');"
                                >
                                    <input
                                        type="hidden"
                                        name="fish_id"
                                        value="<?= (int) $fish["id"] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars($_SESSION["csrf_token"], ENT_QUOTES, "UTF-8") ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center rounded-lg bg-red-100 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-200 transition"
                                    >
                                        Delete Fish
                                    </button>
                                </form>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const pricingType = document.getElementById("pricing_type");
    const minLabel = document.getElementById("price_min_label");
    const maxWrapper = document.getElementById("price_max_wrapper");
    const maxInput = document.getElementById("price_max");

    function updatePricingFields() {
        const isRange = pricingType.value === "range";

        minLabel.textContent = isRange
            ? "Minimum Price (₹)"
            : "Price (₹)";

        maxWrapper.classList.toggle("hidden", !isRange);
        maxInput.disabled = !isRange;
        maxInput.required = isRange;
    }

    pricingType.addEventListener("change", updatePricingFields);
    updatePricingFields();
});
</script>

</body>
</html>
