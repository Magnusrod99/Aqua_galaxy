
<?php
session_start();

require_once __DIR__ . "/../config/app.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: " . BASE_URL . "/admin/login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

$pageTitle = "Fish Categories | Aqua Galaxy";
$categories = [];
$error = "";
$success = "";

// Generate a URL-friendly slug from the category name
function createCategorySlug(string $name): string
{
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

// Process category creation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($name === "" || strlen($name) > 100) {
        $error = "Enter a category name of up to 100 characters.";
    } elseif (strlen($description) > 1000) {
        $error = "The description must be 1000 characters or fewer.";
    } else {
        $slug = createCategorySlug($name);

        if ($slug === "") {
            $error = "Use a category name containing English letters or numbers.";
        } else {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO fish_categories
                        (name, slug, description, status)
                     VALUES
                        (:name, :slug, :description, 1)"
                );

                $stmt->execute([
                    "name" => $name,
                    "slug" => $slug,
                    "description" => $description !== "" ? $description : null
                ]);

                // Prevent the form from being submitted twice on refresh
                header("Location: " . BASE_URL . "/admin/fish-categories.php?created=1");
                exit;

            } catch (PDOException $e) {
                if ($e->getCode() === "23000") {
                    $error = "A category with this name or URL slug already exists.";
                } else {
                    error_log("Fish category creation failed: " . $e->getMessage());
                    $error = "Unable to create the category. Please try again.";
                }
            }
        }
    }
}

if (isset($_GET["created"]) && $_GET["created"] === "1") {
    $success = "Fish category created successfully!";
}

// Load categories
try {
    $stmt = $pdo->query(
        "SELECT id, name, slug, description, status, created_at
         FROM fish_categories
         ORDER BY id DESC"
    );

    $categories = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Fish category listing failed: " . $e->getMessage());
    $error = "Unable to load fish categories. Please try again.";
}

require_once __DIR__ . "/../includes/header.php";
?>

<main class="min-h-screen bg-slate-50 px-4 py-8 sm:px-8">

    <div class="max-w-7xl mx-auto">

        <!-- Page Heading -->
        <div class="mb-8">
            <a href="<?= BASE_URL ?>/admin/dashboard.php"
               class="text-sm text-sky-600 hover:text-sky-700">
                ← Back to Dashboard
            </a>

            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-3">
                Fish Categories
            </h1>

            <p class="text-slate-500 mt-2">
                Create and organize categories for your ornamental fish.
            </p>
        </div>

        <!-- Feedback -->
        <?php if ($error !== ""): ?>
            <div role="alert" class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6">
                <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ""): ?>
            <div role="status" class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6">
                <?= htmlspecialchars($success, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Add Category Form -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sm:p-6">

                <h2 class="text-lg font-bold text-slate-900">
                    Add New Category
                </h2>

                <p class="text-sm text-slate-500 mt-1 mb-6">
                    Enter a name and optional description.
                </p>

                <form method="POST" action="<?= BASE_URL ?>/admin/fish-categories.php" class="space-y-5">

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                            Category Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            maxlength="100"
                            required
                            placeholder="e.g. Goldfish"
                            value="<?= htmlspecialchars($_POST["name"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400"
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                            Description <span class="text-slate-400">(Optional)</span>
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            maxlength="1000"
                            placeholder="Describe this fish category..."
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400"
                        ><?= htmlspecialchars($_POST["description"] ?? "", ENT_QUOTES, "UTF-8") ?></textarea>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 rounded-lg transition"
                    >
                        + Add Category
                    </button>

                </form>

            </section>

            <!-- Category List -->
            <section class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            All Fish Categories
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Categories stored in your database.
                        </p>
                    </div>

                    <span class="shrink-0 bg-sky-100 text-sky-700 px-3 py-2 rounded-full text-sm font-semibold">
                        <?= count($categories) ?> total
                    </span>
                </div>

                <?php if (empty($categories)): ?>

                    <div class="px-6 py-12 text-center">
                        <div class="text-5xl mb-4">🐠</div>
                        <h3 class="text-lg font-semibold text-slate-900">
                            No categories yet
                        </h3>
                        <p class="text-sm text-slate-500 mt-2">
                            Create your first fish category using the form.
                        </p>
                    </div>

                <?php else: ?>

                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left">

                            <thead class="bg-slate-50 text-slate-600 text-sm">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">Category</th>
                                    <th class="px-5 py-4 font-semibold">Slug</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($categories as $category): ?>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-slate-900">
                                                <?= htmlspecialchars($category["name"], ENT_QUOTES, "UTF-8") ?>
                                            </p>
                                            <p class="text-xs text-slate-500 mt-1">
                                                <?= htmlspecialchars($category["description"] ?? "", ENT_QUOTES, "UTF-8") ?>
                                            </p>
                                        </td>

                                        <td class="px-5 py-4 text-sm text-slate-600">
                                            <?= htmlspecialchars($category["slug"], ENT_QUOTES, "UTF-8") ?>
                                        </td>

                                        <td class="px-5 py-4">
                                            <?php if ((int) $category["status"] === 1): ?>
                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                    Active
                                                </span>
                                            <?php else: ?>
                                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-semibold">
                                                    Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden divide-y divide-slate-100">
                        <?php foreach ($categories as $category): ?>
                            <article class="p-5">

                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-semibold text-slate-900">
                                        <?= htmlspecialchars($category["name"], ENT_QUOTES, "UTF-8") ?>
                                    </h3>

                                    <?php if ((int) $category["status"] === 1): ?>
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-full text-xs font-semibold">
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <p class="text-sm text-sky-600 mt-2">
                                    <?= htmlspecialchars($category["slug"], ENT_QUOTES, "UTF-8") ?>
                                </p>

                                <?php if (!empty($category["description"])): ?>
                                    <p class="text-sm text-slate-600 mt-2">
                                        <?= htmlspecialchars($category["description"], ENT_QUOTES, "UTF-8") ?>
                                    </p>
                                <?php endif; ?>

                            </article>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>

            </section>

        </div>

    </div>

</main>

</body>
</html>
