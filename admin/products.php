<?php
require_once __DIR__ . '/admin_header.php';
$pageTitle   = 'Products | Aqua Galaxy Admin';
$currentPage = 'products';

$error = ''; $success = '';

// ── Load categories ──────────────────────────────────────────
$catList = [];
try {
    $catList = $pdo->query("SELECT id,name FROM aquarium_product_categories WHERE status=1 ORDER BY name")->fetchAll();
} catch (PDOException $e) {
    // table might not exist yet – try without category filter
    try { $catList = $pdo->query("SELECT id,name FROM aquarium_product_categories ORDER BY name")->fetchAll(); }
    catch (PDOException $e2) {}
}

// ── Handle create / update ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed.';
    } else {
        $id    = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $name  = trim($_POST['name'] ?? '');
        $catId = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
        $desc  = trim($_POST['description'] ?? '');
        $price = $_POST['price'] ?? '';
        $stock = trim($_POST['stock'] ?? '');
        $status = ($_POST['status'] ?? '0') === '1' ? 1 : 0;

        if ($name === '' || strlen($name) > 150) { $error = 'Product name required (max 150 chars).'; }
        elseif (!is_numeric($price) || $price < 0) { $error = 'Enter a valid price.'; }
        else {
            try {
                if ($id) {
                    $pdo->prepare("UPDATE aquarium_products SET name=:n,category_id=:c,description=:d,price=:p,stock=:s,status=:st WHERE id=:id")
                        ->execute(['n'=>$name,'c'=>$catId,'d'=>$desc!==''?$desc:null,'p'=>$price,'s'=>$stock!==''?$stock:null,'st'=>$status,'id'=>$id]);
                    $success = 'Product updated.';
                } else {
                    $pdo->prepare("INSERT INTO aquarium_products (name,category_id,description,price,stock,status,created_at) VALUES (:n,:c,:d,:p,:s,:st,NOW())")
                        ->execute(['n'=>$name,'c'=>$catId,'d'=>$desc!==''?$desc:null,'p'=>$price,'s'=>$stock!==''?$stock:null,'st'=>$status]);
                    $success = 'Product created.';
                }
                header('Location: ' . BASE_URL . '/admin/products.php?ok=1'); exit;
            } catch (PDOException $e) { $error = 'Database error: ' . $e->getMessage(); }
        }
    }
}
// ── Handle delete via GET ───────────────────────────────────
if (isset($_GET['delete'])) {
    $delId = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($delId) {
        try {
            $pdo->prepare("DELETE FROM aquarium_products WHERE id=:id")->execute(['id'=>$delId]);
            header('Location: ' . BASE_URL . '/admin/products.php?deleted=1'); exit;
        } catch (PDOException $e) { $error = 'Could not delete product.'; }
    }
}
if (isset($_GET['ok']))      $success = 'Product saved successfully!';
if (isset($_GET['deleted'])) $success = 'Product deleted.';

// ── Load edit target ────────────────────────────────────────
$editing = null;
if (isset($_GET['edit'])) {
    $eId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($eId) { try { $st=$pdo->prepare("SELECT * FROM aquarium_products WHERE id=:id"); $st->execute(['id'=>$eId]); $editing=$st->fetch(); } catch(PDOException $e){} }
}

// ── Load all products ───────────────────────────────────────
$products = [];
try {
    $products = $pdo->query("SELECT p.*, c.name AS cat_name
                             FROM aquarium_products p
                             LEFT JOIN aquarium_product_categories c ON p.category_id=c.id
                             ORDER BY p.created_at DESC")->fetchAll();
} catch (PDOException $e) {}

include __DIR__ . '/admin_layout_head.php';
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
  <div>
    <h1 class="text-2xl font-bold text-slate-900">Products</h1>
    <p class="text-sm text-slate-500 mt-1">Manage your aquarium products catalogue.</p>
  </div>
</div>

<?php if ($error): ?><div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<?php if ($success): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($success,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>

<!-- ── Form ────────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-7">
  <h2 class="text-base font-semibold text-slate-800 mb-4">
    <?= $editing ? '✏️ Edit Product' : '＋ Add New Product' ?>
  </h2>
  <form method="POST" class="grid sm:grid-cols-2 gap-4">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Product Name <span class="text-red-500">*</span></label>
      <input type="text" name="name" required maxlength="150"
             value="<?= htmlspecialchars($editing['name']??'',ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. Aquarium Filter">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
      <select name="category_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
        <option value="">— Select —</option>
        <?php foreach ($catList as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= ($editing && $editing['category_id']==$cat['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name'],ENT_QUOTES,'UTF-8') ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Price (₹) <span class="text-red-500">*</span></label>
      <input type="number" name="price" step="0.01" min="0" required
             value="<?= htmlspecialchars($editing['price']??'',ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="0.00">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Stock / Unit</label>
      <input type="text" name="stock" maxlength="100"
             value="<?= htmlspecialchars($editing['stock']??'',ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. 10 units">
    </div>
    <div class="sm:col-span-2">
      <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
      <textarea name="description" rows="3"
                class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm resize-none"
                placeholder="Short product description..."><?= htmlspecialchars($editing['description']??'',ENT_QUOTES,'UTF-8') ?></textarea>
    </div>
    <div class="sm:col-span-2 flex items-center gap-6">
      <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer">
        <input type="checkbox" name="status" value="1"
               <?= (isset($editing) && $editing['status']==1) || !$editing ? 'checked' : '' ?>
               class="w-4 h-4 rounded text-sky-600">
        Active (visible on website)
      </label>
      <div class="flex gap-2 ml-auto">
        <?php if ($editing): ?>
        <a href="<?= BASE_URL ?>/admin/products.php"
           class="px-4 py-2 text-sm border border-slate-300 rounded-lg hover:bg-slate-50 transition">Cancel</a>
        <?php endif; ?>
        <button type="submit"
                class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition">
          <?= $editing ? 'Update Product' : '＋ Create Product' ?>
        </button>
      </div>
    </div>
  </form>
</div>

<!-- ── List ─────────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
    <h2 class="text-base font-semibold text-slate-800">All Products (<?= count($products) ?>)</h2>
  </div>
  <?php if (empty($products)): ?>
  <p class="text-slate-400 text-sm text-center py-10">No products yet — add one above.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide">
        <tr>
          <th class="text-left px-5 py-3 font-medium">Name</th>
          <th class="text-left px-5 py-3 font-medium">Category</th>
          <th class="text-left px-5 py-3 font-medium">Price</th>
          <th class="text-left px-5 py-3 font-medium">Status</th>
          <th class="px-5 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($products as $p): ?>
        <tr class="hover:bg-slate-50 transition">
          <td class="px-5 py-3 font-medium text-slate-900"><?= htmlspecialchars($p['name'],ENT_QUOTES,'UTF-8') ?></td>
          <td class="px-5 py-3 text-slate-500"><?= htmlspecialchars($p['cat_name']??'—',ENT_QUOTES,'UTF-8') ?></td>
          <td class="px-5 py-3 font-medium text-slate-800">₹<?= number_format($p['price'],2) ?></td>
          <td class="px-5 py-3">
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                         <?= $p['status'] ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' ?>">
              <?= $p['status'] ? 'Active' : 'Hidden' ?>
            </span>
          </td>
          <td class="px-5 py-3 text-right flex items-center justify-end gap-2">
            <a href="<?= BASE_URL ?>/admin/products.php?edit=<?= $p['id'] ?>"
               class="text-xs font-medium text-sky-600 hover:text-sky-800 px-3 py-1 rounded-lg hover:bg-sky-50 transition">
              Edit
            </a>
            <a href="<?= BASE_URL ?>/admin/products.php?delete=<?= $p['id'] ?>"
               onclick="return confirm('Delete «<?= htmlspecialchars($p['name'],ENT_QUOTES,'UTF-8') ?>»?')"
               class="text-xs font-medium text-red-600 hover:text-red-800 px-3 py-1 rounded-lg hover:bg-red-50 transition">
              Delete
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/admin_layout_foot.php'; ?>
