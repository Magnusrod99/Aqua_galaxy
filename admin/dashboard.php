<?php
require_once __DIR__ . '/admin_header.php';
$pageTitle = 'Dashboard | Aqua Galaxy Admin';
$currentPage = 'dashboard';

// ── Live stats ──────────────────────────────────────────────
$stats = ['fish' => 0, 'categories' => 0, 'products' => 0,
          'tailoring' => 0, 'gallery' => 0, 'enquiries' => 0];
try {
    $stats['fish']       = (int)$pdo->query("SELECT COUNT(*) FROM fish")->fetchColumn();
    $stats['categories'] = (int)$pdo->query("SELECT COUNT(*) FROM fish_categories")->fetchColumn();
    $stats['products']   = (int)$pdo->query("SELECT COUNT(*) FROM aquarium_products")->fetchColumn();
    $stats['tailoring']  = (int)$pdo->query("SELECT COUNT(*) FROM tailoring_services")->fetchColumn();
    $stats['gallery']    = (int)$pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
    $stats['enquiries']  = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status='new'")->fetchColumn();
} catch (PDOException $e) {
    error_log('Dashboard stats error: ' . $e->getMessage());
}

// ── Recent fish ─────────────────────────────────────────────
$recentFish = [];
try {
    $recentFish = $pdo->query("SELECT f.name, fc.name AS category, f.price_min, f.price_max, f.created_at
                               FROM fish f LEFT JOIN fish_categories fc ON f.category_id=fc.id
                               ORDER BY f.created_at DESC LIMIT 5")->fetchAll();
} catch (PDOException $e) {}

include __DIR__ . '/admin_layout_head.php';
?>

<!-- Page heading -->
<div class="mb-6">
  <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
  <p class="text-sm text-slate-500 mt-1">Welcome back, <?= $_adminName ?>. Here's what's happening.</p>
</div>

<!-- ── Stat Cards ──────────────────────────────────────────── -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
  <?php
  $cards = [
    ['label'=>'Fish',       'val'=>$stats['fish'],       'icon'=>'🐠','color'=>'bg-sky-50 text-sky-700',    'link'=>'/admin/fish.php'],
    ['label'=>'Categories', 'val'=>$stats['categories'], 'icon'=>'🏷️','color'=>'bg-indigo-50 text-indigo-700','link'=>'/admin/fish-categories.php'],
    ['label'=>'Products',   'val'=>$stats['products'],   'icon'=>'🪸','color'=>'bg-teal-50 text-teal-700',   'link'=>'/admin/products.php'],
    ['label'=>'Tailoring',  'val'=>$stats['tailoring'],  'icon'=>'✂️','color'=>'bg-pink-50 text-pink-700',   'link'=>'/admin/tailoring.php'],
    ['label'=>'Gallery',    'val'=>$stats['gallery'],     'icon'=>'🖼️','color'=>'bg-amber-50 text-amber-700', 'link'=>'/admin/gallery.php'],
    ['label'=>'Enquiries',  'val'=>$stats['enquiries'],  'icon'=>'📧','color'=>'bg-red-50 text-red-700',     'link'=>'#'],
  ];
  foreach ($cards as $c):
  ?>
  <a href="<?= BASE_URL . $c['link'] ?>"
     class="<?= $c['color'] ?> rounded-2xl p-5 flex flex-col gap-3 shadow-sm hover:shadow-md transition group">
    <div class="flex items-center justify-between">
      <span class="text-3xl"><?= $c['icon'] ?></span>
      <svg class="w-4 h-4 opacity-40 group-hover:opacity-80 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </div>
    <div>
      <p class="text-3xl font-bold"><?= $c['val'] ?></p>
      <p class="text-sm font-medium opacity-70"><?= $c['label'] ?></p>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<!-- ── Quick Actions ──────────────────────────────────────── -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-8">
  <h2 class="text-base font-semibold text-slate-800 mb-4">Quick Actions</h2>
  <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
    <?php
    $actions = [
      ['href'=>'/admin/fish.php',           'label'=>'Add New Fish',    'icon'=>'🐠'],
      ['href'=>'/admin/fish-categories.php','label'=>'Add Category',    'icon'=>'🏷️'],
      ['href'=>'/admin/products.php',       'label'=>'Add Product',     'icon'=>'🪸'],
      ['href'=>'/admin/tailoring.php',      'label'=>'Add Service',     'icon'=>'✂️'],
      ['href'=>'/admin/gallery.php',        'label'=>'Upload Image',    'icon'=>'📷'],
      ['href'=>'/',                          'label'=>'View Website',    'icon'=>'🌐'],
    ];
    foreach ($actions as $a):
    ?>
    <a href="<?= BASE_URL . $a['href'] ?>"
       class="flex items-center gap-2 px-4 py-3 bg-slate-50 hover:bg-sky-50 hover:text-sky-700 rounded-xl text-sm font-medium transition border border-slate-200 hover:border-sky-200">
      <span><?= $a['icon'] ?></span><?= $a['label'] ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── Recent Fish ──────────────────────────────────────────── -->
<?php if (!empty($recentFish)): ?>
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-base font-semibold text-slate-800">Recently Added Fish</h2>
    <a href="<?= BASE_URL ?>/admin/fish.php" class="text-sm text-sky-600 hover:underline">View all →</a>
  </div>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-xs text-slate-500 uppercase tracking-wide border-b border-slate-100">
          <th class="pb-2 font-medium">Name</th>
          <th class="pb-2 font-medium">Category</th>
          <th class="pb-2 font-medium">Price</th>
          <th class="pb-2 font-medium">Added</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        <?php foreach ($recentFish as $f): ?>
        <tr>
          <td class="py-2.5 font-medium text-slate-800"><?= htmlspecialchars($f['name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="py-2.5 text-slate-500"><?= htmlspecialchars($f['category'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td class="py-2.5 text-slate-700">
            ₹<?= number_format($f['price_min'], 0) ?>
            <?= $f['price_max'] != $f['price_min'] ? ' – ₹' . number_format($f['price_max'], 0) : '' ?>
          </td>
          <td class="py-2.5 text-slate-400"><?= date('d M', strtotime($f['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/admin_layout_foot.php'; ?>
