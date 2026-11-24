<?php
require_once __DIR__ . '/admin_header.php';
$pageTitle   = 'Fish Management | Aqua Galaxy Admin';
$currentPage = 'fish';

$error = ''; $success = '';

function fishSlug(string $n): string {
    return trim(preg_replace('/[^a-z0-9]+/','-',strtolower(trim($n))),'-');
}

// ── Handle messages from redirects ───────────────────────────
if (isset($_GET['created'])) $success = 'Fish added successfully!';
if (isset($_GET['deleted'])) $success = 'Fish deleted.';
if (isset($_GET['delete_error'])) {
    $errMap = [
        'invalid'  => 'Invalid fish selected.',
        'notfound' => 'Fish not found.',
        'security' => 'Security check failed.',
        'failed'   => 'Could not delete — fish may be linked to other records.',
    ];
    $error = $errMap[$_GET['delete_error']] ?? 'An error occurred.';
}

// ── Load categories ──────────────────────────────────────────
$categories = [];
try {
    $categories = $pdo->query("SELECT id,name FROM fish_categories WHERE status=1 ORDER BY name")->fetchAll();
} catch (PDOException $e) {}

// ── Handle create ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please try again.';
    } else {
        $name        = trim($_POST['name'] ?? '');
        $catId       = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
        $desc        = trim($_POST['description'] ?? '');
        $pricingType = $_POST['pricing_type'] ?? 'fixed';
        $priceMin    = $_POST['price_min'] ?? '';
        $priceMax    = $_POST['price_max'] ?? '';
        $careLevel   = $_POST['care_level'] ?? '';
        $tankSize    = trim($_POST['tank_size'] ?? '');
        $temperature = trim($_POST['temperature'] ?? '');
        $food        = trim($_POST['food'] ?? '');

        if ($name === '' || strlen($name) > 150)        { $error = 'Fish name required (max 150 chars).'; }
        elseif (!$catId)                                { $error = 'Please select a category.'; }
        elseif (!is_numeric($priceMin) || $priceMin < 0){ $error = 'Enter a valid min price.'; }
        elseif (!in_array($careLevel,['easy','medium','hard'],true)) { $error = 'Select a care level.'; }
        else {
            $min = round((float)$priceMin, 2);
            $max = $pricingType === 'range' ? round((float)$priceMax, 2) : $min;
            if ($pricingType === 'range' && $max < $min) { $error = 'Max price must be ≥ min price.'; }
            else {
                $slug = fishSlug($name);
                if ($slug === '') { $error = 'Use English letters/numbers in the name.'; }
                else {
                    try {
                        $pdo->prepare("INSERT INTO fish (category_id,name,slug,description,price_min,price_max,care_level,tank_size,temperature,food,status,created_at)
                                       VALUES (:c,:n,:s,:d,:pmin,:pmax,:cl,:ts,:tp,:fd,1,NOW())")
                            ->execute(['c'=>$catId,'n'=>$name,'s'=>$slug,
                                       'd'=>$desc!==''?$desc:null,
                                       'pmin'=>$min,'pmax'=>$max,'cl'=>$careLevel,
                                       'ts'=>$tankSize!==''?$tankSize:null,
                                       'tp'=>$temperature!==''?$temperature:null,
                                       'fd'=>$food!==''?$food:null]);
                        header('Location: ' . BASE_URL . '/admin/fish.php?created=1');
                        exit;
                    } catch (PDOException $e) {
                        $error = $e->getCode()==='23000'
                            ? 'A fish with this name already exists.'
                            : 'Could not save fish.';
                    }
                }
            }
        }
    }
}

// ── Load fish list ───────────────────────────────────────────
$fishList = [];
try {
    $fishList = $pdo->query("SELECT f.*,fc.name AS cat_name
                             FROM fish f
                             LEFT JOIN fish_categories fc ON f.category_id=fc.id
                             ORDER BY f.created_at DESC")->fetchAll();
} catch (PDOException $e) {}

include __DIR__ . '/admin_layout_head.php';
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
  <div>
    <h1 class="text-2xl font-bold text-slate-900">Fish Management</h1>
    <p class="text-sm text-slate-500 mt-1">Add and manage ornamental fish listings.</p>
  </div>
  <a href="<?= BASE_URL ?>/admin/fish-categories.php"
     class="text-sm text-sky-600 hover:text-sky-800 font-medium border border-sky-200 hover:bg-sky-50 px-4 py-2 rounded-lg transition">
    🏷️ Manage Categories
  </a>
</div>

<?php if ($error): ?><div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<?php if ($success): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($success,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>

<!-- ── Add Form ────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-7">
  <h2 class="text-base font-semibold text-slate-800 mb-5">＋ Add New Fish</h2>
  <form method="POST" class="grid sm:grid-cols-2 gap-4">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Fish Name <span class="text-red-500">*</span></label>
      <input type="text" name="name" required maxlength="150"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. Clownfish">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
      <select name="category_id" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
        <option value="">— Select —</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name'],ENT_QUOTES,'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="sm:col-span-2">
      <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
      <textarea name="description" rows="2" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm resize-none"
                placeholder="Short description..."></textarea>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Pricing Type</label>
      <select name="pricing_type" id="pricingType" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
        <option value="fixed">Fixed Price</option>
        <option value="range">Price Range</option>
      </select>
    </div>
    <div>
      <label id="priceMinLabel" class="block text-sm font-medium text-slate-700 mb-1">Price (₹) <span class="text-red-500">*</span></label>
      <input type="number" name="price_min" id="priceMin" step="0.01" min="0" required
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="0.00">
    </div>
    <div id="priceMaxWrapper" class="hidden">
      <label class="block text-sm font-medium text-slate-700 mb-1">Max Price (₹) <span class="text-red-500">*</span></label>
      <input type="number" name="price_max" id="priceMax" step="0.01" min="0" disabled
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="0.00">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Care Level <span class="text-red-500">*</span></label>
      <select name="care_level" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
        <option value="easy">Easy</option>
        <option value="medium">Medium</option>
        <option value="hard">Hard</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Tank Size</label>
      <input type="text" name="tank_size" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. 20 gallons">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Temperature</label>
      <input type="text" name="temperature" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. 24–28 °C">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Food</label>
      <input type="text" name="food" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. Flakes, live food">
    </div>
    <div class="sm:col-span-2 pt-1">
      <button type="submit"
              class="w-full sm:w-auto px-6 py-3 bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white text-base font-bold rounded-xl transition shadow-md">
        🐠 Add Fish
      </button>
    </div>
  </form>
</div>

<!-- ── Fish List ──────────────────────────────────────────── -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100">
    <h2 class="text-base font-semibold text-slate-800">All Fish (<?= count($fishList) ?>)</h2>
  </div>
  <?php if (empty($fishList)): ?>
  <p class="text-slate-400 text-sm text-center py-10">No fish added yet — use the form above.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wide">
        <tr>
          <th class="text-left px-5 py-3 font-medium">Name</th>
          <th class="text-left px-5 py-3 font-medium hidden sm:table-cell">Category</th>
          <th class="text-left px-5 py-3 font-medium">Price</th>
          <th class="text-left px-5 py-3 font-medium hidden sm:table-cell">Care</th>
          <th class="px-5 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($fishList as $f): ?>
        <tr class="hover:bg-slate-50 transition">
          <td class="px-5 py-3 font-medium text-slate-900"><?= htmlspecialchars($f['name'],ENT_QUOTES,'UTF-8') ?></td>
          <td class="px-5 py-3 text-slate-500 hidden sm:table-cell"><?= htmlspecialchars($f['cat_name']??'—',ENT_QUOTES,'UTF-8') ?></td>
          <td class="px-5 py-3 text-slate-700">
            ₹<?= number_format($f['price_min'],0) ?>
            <?= $f['price_max'] != $f['price_min'] ? ' – ₹' . number_format($f['price_max'],0) : '' ?>
          </td>
          <td class="px-5 py-3 hidden sm:table-cell">
            <?php $cl = $f['care_level'] ?? 'easy';
                  $clr = ['easy'=>'bg-green-100 text-green-700','medium'=>'bg-amber-100 text-amber-700','hard'=>'bg-red-100 text-red-700'];
            ?>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $clr[$cl] ?? $clr['easy'] ?>">
              <?= ucfirst($cl) ?>
            </span>
          </td>
          <td class="px-5 py-3 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="<?= BASE_URL ?>/admin/edit-fish.php?id=<?= $f['id'] ?>"
                 class="text-xs font-medium text-sky-600 hover:text-sky-800 px-3 py-1 rounded-lg hover:bg-sky-50 transition">
                Edit
              </a>
              <form method="POST" action="<?= BASE_URL ?>/admin/delete-fish.php"
                    onsubmit="return confirm('Delete «<?= htmlspecialchars($f['name'],ENT_QUOTES,'UTF-8') ?>»?')">
                <input type="hidden" name="fish_id" value="<?= $f['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit"
                        class="text-xs font-medium text-red-600 hover:text-red-800 px-3 py-1 rounded-lg hover:bg-red-50 transition">
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
  <?php endif; ?>
</div>

<script>
(function(){
  const type = document.getElementById('pricingType');
  const minLabel = document.getElementById('priceMinLabel');
  const maxWrap = document.getElementById('priceMaxWrapper');
  const maxInput = document.getElementById('priceMax');
  function update(){
    const isRange = type.value === 'range';
    minLabel.textContent = (isRange ? 'Min Price (₹) *' : 'Price (₹) *');
    maxWrap.classList.toggle('hidden', !isRange);
    maxInput.disabled = !isRange;
    maxInput.required = isRange;
  }
  type.addEventListener('change', update);
  update();
})();
</script>

<?php include __DIR__ . '/admin_layout_foot.php'; ?>
