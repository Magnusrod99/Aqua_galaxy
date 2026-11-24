<?php
require_once __DIR__ . '/admin_header.php';
$pageTitle   = 'Fish Categories | Aqua Galaxy Admin';
$currentPage = 'fish-categories';

// ── Handle Create / Delete ───────────────────────────────────
$error = ''; $success = '';

function catSlug(string $n): string {
    return trim(preg_replace('/[^a-z0-9]+/','-',strtolower(trim($n))),'-');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? 'create';
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please try again.';
    } elseif ($action === 'delete') {
        $delId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($delId) {
            try {
                // Prevent deleting category that has fish
                $cnt = (int)$pdo->prepare("SELECT COUNT(*) FROM fish WHERE category_id=?")->execute([$delId]) ? 0 : 0;
                $st = $pdo->prepare("SELECT COUNT(*) FROM fish WHERE category_id=:id");
                $st->execute(['id'=>$delId]);
                $cnt = (int)$st->fetchColumn();
                if ($cnt > 0) {
                    $error = "Cannot delete — this category has $cnt fish linked to it.";
                } else {
                    $pdo->prepare("DELETE FROM fish_categories WHERE id=:id")->execute(['id'=>$delId]);
                    header('Location: ' . BASE_URL . '/admin/fish-categories.php?deleted=1'); exit;
                }
            } catch (PDOException $e) { $error = 'Delete failed: ' . $e->getMessage(); }
        }
    } else {
        // create
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $slug = catSlug($name);
        if ($name === '' || strlen($name) > 100) { $error = 'Name required (max 100 chars).'; }
        elseif ($slug === '')  { $error = 'Use English letters/numbers in the name.'; }
        else {
            try {
                $pdo->prepare("INSERT INTO fish_categories (name,slug,description,status) VALUES (:n,:s,:d,1)")
                    ->execute(['n'=>$name,'s'=>$slug,'d'=>$desc!==''?$desc:null]);
                header('Location: ' . BASE_URL . '/admin/fish-categories.php?created=1'); exit;
            } catch (PDOException $e) {
                $error = $e->getCode()==='23000'
                    ? 'A category with this name already exists.'
                    : 'Could not save category.';
            }
        }
    }
}
if (isset($_GET['created'])) $success = 'Category created successfully!';
if (isset($_GET['deleted'])) $success = 'Category deleted.';

// ── Load list ────────────────────────────────────────────────
$categories = [];
try {
    $categories = $pdo->query("SELECT fc.*, COUNT(f.id) AS fish_count
                               FROM fish_categories fc
                               LEFT JOIN fish f ON f.category_id=fc.id
                               GROUP BY fc.id ORDER BY fc.id DESC")->fetchAll();
} catch (PDOException $e) { $error = 'Could not load categories.'; }

include __DIR__ . '/admin_layout_head.php';
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
  <div>
    <h1 class="text-2xl font-bold text-slate-900">Fish Categories</h1>
    <p class="text-sm text-slate-500 mt-1">Organise your ornamental fish into categories.</p>
  </div>
</div>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($success,ENT_QUOTES,'UTF-8') ?></div>
<?php endif; ?>

<!-- ── Add Form ────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-7">
  <h2 class="text-base font-semibold text-slate-800 mb-4">Add New Category</h2>
  <form method="POST" class="grid sm:grid-cols-2 gap-4">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="_action" value="create">
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Category Name <span class="text-red-500">*</span></label>
      <input type="text" name="name" required maxlength="100"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-sky-400"
             placeholder="e.g. Goldfish">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
      <input type="text" name="description" maxlength="500"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm"
             placeholder="Short description (optional)">
    </div>
    <div class="sm:col-span-2">
      <button type="submit"
              class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition">
        ＋ Create Category
      </button>
    </div>
  </form>
</div>

<!-- ── Category List ──────────────────────────────────────── -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100">
    <h2 class="text-base font-semibold text-slate-800">All Categories (<?= count($categories) ?>)</h2>
  </div>
  <?php if (empty($categories)): ?>
  <p class="text-slate-400 text-sm text-center py-10">No categories yet. Add one above.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide">
        <tr>
          <th class="text-left px-5 py-3 font-medium">Name</th>
          <th class="text-left px-5 py-3 font-medium">Slug</th>
          <th class="text-left px-5 py-3 font-medium">Fish</th>
          <th class="text-left px-5 py-3 font-medium">Status</th>
          <th class="px-5 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($categories as $cat): ?>
        <tr class="hover:bg-slate-50 transition">
          <td class="px-5 py-3 font-medium text-slate-900"><?= htmlspecialchars($cat['name'],ENT_QUOTES,'UTF-8') ?></td>
          <td class="px-5 py-3 text-slate-400 font-mono text-xs"><?= htmlspecialchars($cat['slug'],ENT_QUOTES,'UTF-8') ?></td>
          <td class="px-5 py-3">
            <span class="inline-block bg-sky-100 text-sky-700 text-xs font-semibold px-2 py-0.5 rounded-full">
              <?= $cat['fish_count'] ?>
            </span>
          </td>
          <td class="px-5 py-3">
            <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full
                         <?= $cat['status'] ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' ?>">
              <?= $cat['status'] ? 'Active' : 'Hidden' ?>
            </span>
          </td>
          <td class="px-5 py-3 text-right">
            <form method="POST" onsubmit="return confirm('Delete «<?= htmlspecialchars($cat['name'],ENT_QUOTES,'UTF-8') ?>»?')">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
              <input type="hidden" name="_action" value="delete">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
              <button type="submit"
                      class="text-xs text-red-600 hover:text-red-800 font-medium px-3 py-1 rounded-lg hover:bg-red-50 transition">
                Delete
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/admin_layout_foot.php'; ?>
