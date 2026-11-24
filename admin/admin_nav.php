<?php
// Admin navigation bar - mobile-friendly using Tailwind CSS
?>
<nav class="bg-sky-100 border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap items-center justify-between gap-2">
    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="text-sky-700 hover:text-sky-900 font-medium">Dashboard</a>
    <a href="<?= BASE_URL ?>/admin/fish.php" class="text-sky-700 hover:text-sky-900 font-medium">Fish</a>
    <a href="<?= BASE_URL ?>/admin/products.php" class="text-sky-700 hover:text-sky-900 font-medium">Products</a>
    <a href="<?= BASE_URL ?>/admin/tailoring.php" class="text-sky-700 hover:text-sky-900 font-medium">Tailoring</a>
    <a href="<?= BASE_URL ?>/admin/gallery.php" class="text-sky-700 hover:text-sky-900 font-medium">Gallery</a>
    <form action="<?= BASE_URL ?>/admin/logout.php" method="POST" class="inline">
      <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Log Out</button>
    </form>
  </div>
</nav>
