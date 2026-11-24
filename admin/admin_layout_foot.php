<?php
// admin_layout_foot.php – closes the main wrapper opened by admin_layout_head.php
?>
  </div><!-- /max-w-6xl -->
</main><!-- /main -->

<!-- ═══════════════════════════════════════════
     MOBILE BOTTOM NAV
════════════════════════════════════════════ -->
<?php
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF'], '.php');
$mobileNav = [
  ['href' => '/admin/dashboard.php',      'icon' => '🏠', 'label' => 'Home',       'key' => 'dashboard'],
  ['href' => '/admin/fish.php',           'icon' => '🐠', 'label' => 'Fish',        'key' => 'fish'],
  ['href' => '/admin/products.php',       'icon' => '🪸', 'label' => 'Products',    'key' => 'products'],
  ['href' => '/admin/tailoring.php',      'icon' => '✂️', 'label' => 'Tailoring',   'key' => 'tailoring'],
  ['href' => '/admin/gallery.php',        'icon' => '🖼️', 'label' => 'Gallery',     'key' => 'gallery'],
];
?>
<nav class="sm:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-slate-200 flex">
  <?php foreach ($mobileNav as $item):
    $active = ($currentPage === $item['key']);
  ?>
  <a href="<?= BASE_URL . $item['href'] ?>"
     class="flex-1 flex flex-col items-center justify-center py-2 text-xs font-medium transition
            border-t-2 <?= $active ? 'border-sky-500 text-sky-600' : 'border-transparent text-slate-500 hover:text-slate-800' ?>">
    <span class="text-xl leading-tight"><?= $item['icon'] ?></span>
    <?= $item['label'] ?>
  </a>
  <?php endforeach; ?>
</nav>
<div class="sm:hidden h-16"></div><!-- spacer for bottom nav -->

</body>
</html>
