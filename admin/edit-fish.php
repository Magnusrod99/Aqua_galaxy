<?php
require_once __DIR__ . '/admin_header.php';
$pageTitle   = 'Edit Fish | Aqua Galaxy Admin';
$currentPage = 'fish';

$fishId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$fishId || $fishId < 1) {
    header('Location: ' . BASE_URL . '/admin/fish.php');
    exit;
}

$error = ''; $success = '';

function editFishSlug(string $n): string {
    return trim(preg_replace('/[^a-z0-9]+/','-',strtolower(trim($n))),'-');
}

// Load fish
$st = $pdo->prepare("SELECT * FROM fish WHERE id=:id LIMIT 1");
$st->execute(['id'=>$fishId]);
$fish = $st->fetch();
if (!$fish) {
    header('Location: ' . BASE_URL . '/admin/fish.php');
    exit;
}

// Load categories
$categories = $pdo->query("SELECT id,name FROM fish_categories WHERE status=1 ORDER BY name")->fetchAll();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $catId       = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
    $desc        = trim($_POST['description'] ?? '');
    $priceMin    = $_POST['price_min'] ?? '';
    $priceMax    = $_POST['price_max'] ?? '';
    $careLevel   = $_POST['care_level'] ?? '';
    $tankSize    = trim($_POST['tank_size'] ?? '');
    $temperature = trim($_POST['temperature'] ?? '');
    $food        = trim($_POST['food'] ?? '');

    if ($name === '' || strlen($name) > 150) { $error = 'Fish name required (max 150 chars).'; }
    elseif (!$catId) { $error = 'Please select a category.'; }
    elseif (!is_numeric($priceMin) || $priceMin < 0) { $error = 'Enter a valid min price.'; }
    elseif (!is_numeric($priceMax) || $priceMax < $priceMin) { $error = 'Max price must be ≥ min price.'; }
    elseif (!in_array($careLevel, ['easy','medium','hard'], true)) { $error = 'Select a care level.'; }
    else {
        $slug = editFishSlug($name);
        try {
            $pdo->prepare("UPDATE fish SET category_id=:c,name=:n,slug=:s,description=:d,
                           price_min=:pmin,price_max=:pmax,care_level=:cl,tank_size=:ts,
                           temperature=:tp,food=:fd,updated_at=NOW() WHERE id=:id")
                ->execute(['c'=>$catId,'n'=>$name,'s'=>$slug,'d'=>$desc!==''?$desc:null,
                           'pmin'=>$priceMin,'pmax'=>$priceMax,'cl'=>$careLevel,
                           'ts'=>$tankSize!==''?$tankSize:null,'tp'=>$temperature!==''?$temperature:null,
                           'fd'=>$food!==''?$food:null,'id'=>$fishId]);
            header('Location: ' . BASE_URL . '/admin/edit-fish.php?id=' . $fishId . '&ok=1');
            exit;
        } catch (PDOException $e) { $error = 'Update failed: ' . $e->getMessage(); }
    }
    // Preserve submitted values on error
    $fish = array_merge($fish, compact('name','catId','desc','priceMin','priceMax','careLevel','tankSize','temperature','food'));
    $fish['category_id'] = $catId ?? $fish['category_id'];
}
if (isset($_GET['ok'])) $success = 'Fish updated successfully!';

include __DIR__ . '/admin_layout_head.php';
?>

<div class="mb-6 flex items-center gap-3">
  <a href="<?= BASE_URL ?>/admin/fish.php"
     class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-800 transition">
    ←
  </a>
  <div>
    <h1 class="text-2xl font-bold text-slate-900">Edit Fish</h1>
    <p class="text-sm text-slate-500 mt-0.5">Editing: <?= htmlspecialchars($fish['name'],ENT_QUOTES,'UTF-8') ?></p>
  </div>
</div>

<?php if ($error): ?><div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<?php if ($success): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($success,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
  <form method="POST" class="grid sm:grid-cols-2 gap-5">
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Fish Name <span class="text-red-500">*</span></label>
      <input type="text" name="name" required maxlength="150"
             value="<?= htmlspecialchars($fish['name'],ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
      <select name="category_id" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
        <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= $fish['category_id']==$cat['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name'],ENT_QUOTES,'UTF-8') ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="sm:col-span-2">
      <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
      <textarea name="description" rows="3" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm resize-none"><?= htmlspecialchars($fish['description']??'',ENT_QUOTES,'UTF-8') ?></textarea>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Min Price (₹) <span class="text-red-500">*</span></label>
      <input type="number" name="price_min" step="0.01" min="0" required
             value="<?= htmlspecialchars((string)$fish['price_min'],ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Max Price (₹) <span class="text-red-500">*</span>
        <span class="text-xs text-slate-400">(same as min for fixed price)</span></label>
      <input type="number" name="price_max" step="0.01" min="0" required
             value="<?= htmlspecialchars((string)$fish['price_max'],ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Care Level <span class="text-red-500">*</span></label>
      <select name="care_level" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
        <?php foreach (['easy'=>'Easy','medium'=>'Medium','hard'=>'Hard'] as $val=>$lbl): ?>
        <option value="<?= $val ?>" <?= $fish['care_level']===$val ? 'selected' : '' ?>><?= $lbl ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Tank Size</label>
      <input type="text" name="tank_size"
             value="<?= htmlspecialchars($fish['tank_size']??'',ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. 20 gallons">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Temperature</label>
      <input type="text" name="temperature"
             value="<?= htmlspecialchars($fish['temperature']??'',ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. 24–28 °C">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Food</label>
      <input type="text" name="food"
             value="<?= htmlspecialchars($fish['food']??'',ENT_QUOTES,'UTF-8') ?>"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. Flakes, live food">
    </div>
    <div class="sm:col-span-2 flex gap-3 pt-2">
      <button type="submit"
              class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition">
        💾 Save Changes
      </button>
      <a href="<?= BASE_URL ?>/admin/fish.php"
         class="px-5 py-2.5 border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition">
        Cancel
      </a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/admin_layout_foot.php'; ?>
