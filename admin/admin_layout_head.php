<?php
/**
 * admin_layout_head.php
 * Outputs the full HTML head + top-nav sidebar layout open tags.
 * Call after admin_header.php. Set $pageTitle before including.
 */
$pageTitle   = $pageTitle ?? 'Admin | Aqua Galaxy';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

<!-- Tailwind CSS -->
<script>window.tailwind={config:{}};</script>
<script src="https://cdn.tailwindcss.com"></script>

<style>
  /* Mobile bottom nav active indicator */
  .nav-active { color: #0284c7; border-color: #0284c7; }
  /* smooth sidebar */
  #sidebar { transition: transform .25s ease; }
  /* table row hover */
  tbody tr:hover { background:#f8fafc; }
  /* custom scrollbar */
  ::-webkit-scrollbar { width:6px; height:6px; }
  ::-webkit-scrollbar-track { background:#f1f5f9; }
  ::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:3px; }
  /* input focus ring */
  input:focus, select:focus, textarea:focus {
    outline:none; box-shadow:0 0 0 2px #38bdf8;
  }
</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<!-- ═══════════════════════════════════════════
     TOP HEADER BAR
════════════════════════════════════════════ -->
<header class="fixed top-0 left-0 right-0 z-30 bg-white border-b border-slate-200 shadow-sm">
  <div class="flex items-center justify-between px-4 h-14">
    <!-- Logo -->
    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="flex items-center gap-2 font-bold text-lg text-slate-900">
      <span class="text-2xl">🐟</span>
      <span>Aqua <span class="text-sky-600">Galaxy</span></span>
      <span class="hidden sm:inline text-xs font-normal text-slate-400 ml-1">Admin</span>
    </a>
    <!-- Right: user + hamburger -->
    <div class="flex items-center gap-3">
      <span class="hidden sm:block text-sm text-slate-500"><?= $_adminName ?></span>
      <form action="<?= BASE_URL ?>/admin/logout.php" method="POST">
        <button class="text-sm px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-medium transition">
          Logout
        </button>
      </form>
      <!-- Hamburger for mobile sidebar -->
      <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')"
              class="sm:hidden p-2 rounded-lg hover:bg-slate-100">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>
</header>

<!-- ═══════════════════════════════════════════
     SIDEBAR (desktop fixed, mobile slide-in)
════════════════════════════════════════════ -->
<?php
$nav = [
  ['href' => '/admin/dashboard.php',      'icon' => '🏠', 'label' => 'Dashboard',   'key' => 'dashboard'],
  ['href' => '/admin/fish-categories.php','icon' => '🏷️', 'label' => 'Categories',  'key' => 'fish-categories'],
  ['href' => '/admin/fish.php',           'icon' => '🐠', 'label' => 'Fish',         'key' => 'fish'],
  ['href' => '/admin/products.php',       'icon' => '🪸', 'label' => 'Products',     'key' => 'products'],
  ['href' => '/admin/tailoring.php',      'icon' => '✂️', 'label' => 'Tailoring',    'key' => 'tailoring'],
  ['href' => '/admin/gallery.php',        'icon' => '🖼️', 'label' => 'Gallery',      'key' => 'gallery'],
];
?>
<!-- Sidebar backdrop (mobile) -->
<div id="sidebarBackdrop"
     onclick="document.getElementById('sidebar').classList.add('-translate-x-full');this.classList.add('hidden')"
     class="hidden fixed inset-0 z-20 bg-black/40 sm:hidden"></div>

<aside id="sidebar"
  class="fixed top-14 left-0 bottom-0 z-20 w-56 bg-white border-r border-slate-200 overflow-y-auto
         -translate-x-full sm:translate-x-0 transition-transform">
  <nav class="p-3 space-y-1">
    <?php foreach ($nav as $item):
      $active = ($currentPage === $item['key']);
    ?>
    <a href="<?= BASE_URL . $item['href'] ?>"
       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
              <?= $active
                ? 'bg-sky-50 text-sky-700 font-semibold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
      <span class="text-lg leading-none"><?= $item['icon'] ?></span>
      <?= $item['label'] ?>
      <?php if ($active): ?>
        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-sky-500"></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
    <div class="pt-3 border-t border-slate-100">
      <a href="<?= BASE_URL ?>/" target="_blank"
         class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-500 hover:bg-slate-100 transition">
        <span class="text-lg">🌐</span> View Website
      </a>
    </div>
  </nav>
</aside>

<!-- ═══════════════════════════════════════════
     MAIN CONTENT WRAPPER
════════════════════════════════════════════ -->
<main class="pt-14 sm:pl-56 min-h-screen">
  <div class="p-4 sm:p-6 lg:p-8 max-w-6xl">
