
<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/app.php";

$error = "";
$success = "";

// Get the selected fish ID from the URL
$fishId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$fishId || $fishId < 1) {
    exit("Invalid fish ID.");
}

// Load the existing fish details
$stmt = $pdo->prepare(
    "SELECT * FROM fish WHERE id = :id LIMIT 1"
);
$stmt->execute(["id" => $fishId]);

$fish = $stmt->fetch();

if (!$fish) {
    exit("Fish not found.");
}

// Generate a URL-friendly slug
function createFishSlug($name)
{
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $categoryId = filter_input(
        INPUT_POST,
        "category_id",
        FILTER_VALIDATE_INT
    );
    $description = trim($_POST["description"] ?? "");
    $priceMin = filter_input(
        INPUT_POST,
        "price_min",
        FILTER_VALIDATE_FLOAT
    );
    $priceMax = filter_input(
        INPUT_POST,
        "price_max",
        FILTER_VALIDATE_FLOAT
    );
    $careLevel = $_POST["care_level"] ?? "";
    $tankSize = trim($_POST["tank_size"] ?? "");
    $temperature = trim($_POST["temperature"] ?? "");
    $food = trim($_POST["food"] ?? "");

    // Validate the submitted data
    if (
        $name === "" ||
        !$categoryId ||
        $priceMin === false ||
        $priceMin === null ||
        $priceMax === false ||
        $priceMax === null ||
        !is_finite((float) $priceMin) ||
        !is_finite((float) $priceMax) ||
        $priceMin < 0 ||
        $priceMax < 0 ||
        $priceMin > $priceMax ||
        !in_array($careLevel, ["easy", "medium", "hard"], true)
    ) {
        $error = "Please enter valid fish details and prices.";
    } else {

        // Check that the selected category is active
        $checkCategory = $pdo->prepare(
            "SELECT id FROM fish_categories
             WHERE id = :id AND status = 1"
        );

        $checkCategory->execute([
            "id" => $categoryId
        ]);

        if (!$checkCategory->fetch()) {
            $error = "Please select a valid active category.";
        } else {

            $slug = createFishSlug($name);

            if ($slug === "") {
                $error = "Please enter a valid fish name.";
            } else {
                try {

                    // Update the existing fish record
                    $update = $pdo->prepare(
                        "UPDATE fish
                         SET category_id = :category_id,
                             name = :name,
                             slug = :slug,
                             description = :description,
                             price_min = :price_min,
                             price_max = :price_max,
                             care_level = :care_level,
                             tank_size = :tank_size,
                             temperature = :temperature,
                             food = :food,
                             updated_at = CURRENT_TIMESTAMP
                         WHERE id = :id"
                    );

                    $update->execute([
                        "category_id" => $categoryId,
                        "name" => $name,
                        "slug" => $slug,
                        "description" => $description,
                        "price_min" => $priceMin,
                        "price_max" => $priceMax,
                        "care_level" => $careLevel,
                        "tank_size" => $tankSize,
                        "temperature" => $temperature,
                        "food" => $food,
                        "id" => $fishId
                    ]);

                    // Redirect to prevent duplicate form submissions
                    header(
                        "Location: " . BASE_URL .
                        "/admin/edit-fish.php?id=" .
                        $fishId . "&updated=1"
                    );
                    exit;

                } catch (PDOException $e) {

                    if ($e->getCode() === "23000") {
                        $error =
                            "A fish with this name or slug may already exist.";
                    } else {
                        error_log($e->getMessage());
                        $error =
                            "Unable to update the fish. Please try again.";
                    }
                }
            }
        }
    }

    // Preserve submitted values if validation fails
    $fish = array_merge($fish, [
        "name" => $name,
        "category_id" => $categoryId ?: $fish["category_id"],
        "description" => $description,
        "price_min" =>
            $priceMin !== false && $priceMin !== null
                ? $priceMin
                : $fish["price_min"],
        "price_max" =>
            $priceMax !== false && $priceMax !== null
                ? $priceMax
                : $fish["price_max"],
        "care_level" => $careLevel,
        "tank_size" => $tankSize,
        "temperature" => $temperature,
        "food" => $food
    ]);
}

// Display the success message after redirect
if (
    isset($_GET["updated"]) &&
    $_GET["updated"] === "1"
) {
    $success = "Fish details updated successfully!";
}

// Load active fish categories
$categoryStmt = $pdo->query(
    "SELECT id, name FROM fish_categories
     WHERE status = 1
     ORDER BY name ASC"
);

$categories = $categoryStmt->fetchAll();

$pageTitle = "Edit Fish";
require_once __DIR__ . "/../includes/header.php";
?>

<?php require_once __DIR__ . "/../includes/navbar.php"; ?>

<main class="min-h-screen bg-gray-100 px-4 py-8">
    <div class="mx-auto max-w-3xl">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Edit Fish
            </h1>

            <p class="mt-1 text-sm text-gray-600">
                Update the details of
                <?= htmlspecialchars($fish["name"]) ?>.
            </p>
        </div>

        <?php if ($success !== ""): ?>
            <div
                class="mb-4 rounded-lg bg-green-100 p-3 text-green-700"
                role="status"
            >
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div
                class="mb-4 rounded-lg bg-red-100 p-3 text-red-700"
                role="alert"
            >
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form
            method="POST"
            action="<?= BASE_URL ?>/admin/edit-fish.php?id=<?= (int) $fish["id"] ?>"
            class="space-y-5 rounded-xl bg-white p-5 shadow-sm sm:p-7"
        >

            <div>
                <label
                    for="name"
                    class="mb-1 block font-medium text-gray-700"
                >
                    Fish Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    maxlength="255"
                    value="<?= htmlspecialchars($fish["name"]) ?>"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
            </div>

            <div>
                <label
                    for="category_id"
                    class="mb-1 block font-medium text-gray-700"
                >
                    Category
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= (int) $category["id"] ?>"
                            <?= (int) $fish["category_id"] === (int) $category["id"] ? "selected" : "" ?>
                        >
                            <?= htmlspecialchars($category["name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label
                    for="description"
                    class="mb-1 block font-medium text-gray-700"
                >
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                ><?= htmlspecialchars($fish["description"] ?? "") ?></textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div>
                    <label
                        for="price_min"
                        class="mb-1 block font-medium text-gray-700"
                    >
                        Minimum Price (₹)
                    </label>

                    <input
                        type="number"
                        id="price_min"
                        name="price_min"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string) $fish["price_min"]) ?>"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label
                        for="price_max"
                        class="mb-1 block font-medium text-gray-700"
                    >
                        Maximum Price (₹)
                    </label>

                    <input
                        type="number"
                        id="price_max"
                        name="price_max"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string) $fish["price_max"]) ?>"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
                    >
                </div>

            </div>

            <p class="text-sm text-gray-500">
                For a fixed price, enter the same minimum and maximum price.
            </p>

            <div>
                <label
                    for="care_level"
                    class="mb-1 block font-medium text-gray-700"
                >
                    Care Level
                </label>

                <select
                    id="care_level"
                    name="care_level"
                    required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
                    <?php foreach (["easy", "medium", "hard"] as $level): ?>
                        <option
                            value="<?= $level ?>"
                            <?= $fish["care_level"] === $level ? "selected" : "" ?>
                        >
                            <?= ucfirst($level) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label
                    for="tank_size"
                    class="mb-1 block font-medium text-gray-700"
                >
                    Tank Size
                </label>

                <input
                    type="text"
                    id="tank_size"
                    name="tank_size"
                    value="<?= htmlspecialchars($fish["tank_size"] ?? "") ?>"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
            </div>

            <div>
                <label
                    for="temperature"
                    class="mb-1 block font-medium text-gray-700"
                >
                    Temperature
                </label>

                <input
                    type="text"
                    id="temperature"
                    name="temperature"
                    value="<?= htmlspecialchars($fish["temperature"] ?? "") ?>"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
            </div>

            <div>
                <label
                    for="food"
                    class="mb-1 block font-medium text-gray-700"
                >
                    Food
                </label>

                <input
                    type="text"
                    id="food"
                    name="food"
                    value="<?= htmlspecialchars($fish["food"] ?? "") ?>"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2"
                >
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700"
                >
                    Save Changes
                </button>

                <a
                    href="<?= BASE_URL ?>/admin/fish.php"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-center font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
