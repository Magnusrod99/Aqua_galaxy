<?php
require_once __DIR__ . '/admin_header.php';
$pageTitle   = 'Gallery | Aqua Galaxy Admin';
$currentPage = 'gallery';

$error = ''; $success = '';

// ── Upload folder ────────────────────────────────────────────
define('GALLERY_DIR', dirname(__DIR__) . '/assets/gallery/');
if (!is_dir(GALLERY_DIR)) mkdir(GALLERY_DIR, 0755, true);

// ── Handle upload ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed.';
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select an image to upload.';
    } else {
        $file   = $_FILES['image'];
        $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $extMap  = ['jpeg'=>'jpg','jpg'=>'jpg','png'=>'png','webp'=>'webp','gif'=>'gif'];

        if (!in_array($file['type'], $allowed, true) || !isset($extMap[$ext])) {
            $error = 'Only JPG, PNG, WebP and GIF files are allowed.';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $error = 'File is too large (max 5 MB).';
        } else {
            $newName = uniqid('gallery_', true) . '.' . $extMap[$ext];
            $destPath = GALLERY_DIR . $newName;
            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                $error = 'Upload failed — check folder permissions.';
            } else {
                $caption  = trim($_POST['caption'] ?? '');
                $category = trim($_POST['category'] ?? '');
                try {
                    $pdo->prepare("INSERT INTO gallery (image, caption, category, status, created_at)
                                   VALUES (:img, :cap, :cat, 1, NOW())")
                        ->execute([
                            'img' => $newName,
                            'cap' => $caption !== '' ? $caption : null,
                            'cat' => $category !== '' ? $category : null,
                        ]);
                    header('Location: ' . BASE_URL . '/admin/gallery.php?ok=1'); exit;
                } catch (PDOException $e) {
                    unlink($destPath);
                    $error = 'Database error: ' . $e->getMessage();
                }
            }
        }
    }
}

// ── Handle delete ────────────────────────────────────────────
if (isset($_GET['delete'])) {
    $delId = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    if ($delId) {
        try {
            $st = $pdo->prepare("SELECT image FROM gallery WHERE id=:id"); $st->execute(['id'=>$delId]);
            $row = $st->fetch();
            if ($row) {
                $filePath = GALLERY_DIR . $row['image'];
                if (file_exists($filePath)) unlink($filePath);
                $pdo->prepare("DELETE FROM gallery WHERE id=:id")->execute(['id'=>$delId]);
            }
            header('Location: ' . BASE_URL . '/admin/gallery.php?deleted=1'); exit;
        } catch (PDOException $e) { $error = 'Could not delete image.'; }
    }
}
if (isset($_GET['ok']))      $success = 'Image uploaded successfully!';
if (isset($_GET['deleted'])) $success = 'Image deleted.';

// ── Load gallery ─────────────────────────────────────────────
$images = [];
try {
    $images = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {}

include __DIR__ . '/admin_layout_head.php';
?>

<div class="mb-6">
  <h1 class="text-2xl font-bold text-slate-900">Gallery</h1>
  <p class="text-sm text-slate-500 mt-1">Upload and manage photos shown on the website.</p>
</div>

<?php if ($error): ?><div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<?php if ($success): ?><div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm"><?= htmlspecialchars($success,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>

<!-- Upload Form -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-7">
  <h2 class="text-base font-semibold text-slate-800 mb-4">📷 Upload New Image</h2>
  <form method="POST" enctype="multipart/form-data" class="grid sm:grid-cols-2 gap-4">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="sm:col-span-2">
      <label class="block text-sm font-medium text-slate-700 mb-2">Image File <span class="text-red-500">*</span></label>
      <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-sky-400 transition cursor-pointer"
           onclick="document.getElementById('imageInput').click()">
        <div class="text-3xl mb-2">🖼️</div>
        <p class="text-sm text-slate-500">Click to browse or drag & drop</p>
        <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP, GIF — max 5 MB</p>
        <p id="fileName" class="text-xs text-sky-600 mt-2 font-medium"></p>
      </div>
      <input type="file" id="imageInput" name="image" accept="image/*" class="hidden"
             onchange="document.getElementById('fileName').textContent = this.files[0]?.name || ''">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Caption</label>
      <input type="text" name="caption" maxlength="200"
             class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm" placeholder="e.g. Clownfish display tank">
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
      <select name="category" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
        <option value="">— Select —</option>
        <option value="fish">Fish</option>
        <option value="products">Products</option>
        <option value="tailoring">Tailoring</option>
        <option value="store">Store</option>
      </select>
    </div>
    <div class="sm:col-span-2">
      <button type="submit"
              class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition">
        📤 Upload Image
      </button>
    </div>
  </form>
</div>

<!-- Gallery Grid -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
  <h2 class="text-base font-semibold text-slate-800 mb-5">All Images (<?= count($images) ?>)</h2>
  <?php if (empty($images)): ?>
  <p class="text-slate-400 text-sm text-center py-10">No images uploaded yet.</p>
  <?php else: ?>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($images as $img): ?>
    <?php $imgUrl = BASE_URL . '/assets/gallery/' . htmlspecialchars($img['image'],ENT_QUOTES,'UTF-8'); ?>
    <div class="group relative rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50">
      <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($img['caption']??'',ENT_QUOTES,'UTF-8') ?>"
           class="w-full h-36 object-cover">
      <!-- Overlay on hover -->
      <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-2">
        <a href="<?= $imgUrl ?>" target="_blank"
           class="text-xs text-white bg-white/20 hover:bg-white/30 px-3 py-1 rounded-full transition">View</a>
        <a href="<?= BASE_URL ?>/admin/gallery.php?delete=<?= $img['id'] ?>"
           onclick="return confirm('Delete this image?')"
           class="text-xs text-red-200 bg-red-600/70 hover:bg-red-600 px-3 py-1 rounded-full transition">Delete</a>
      </div>
      <?php if (!empty($img['caption'])): ?>
      <div class="px-2 py-1.5">
        <p class="text-xs text-slate-600 truncate"><?= htmlspecialchars($img['caption'],ENT_QUOTES,'UTF-8') ?></p>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/admin_layout_foot.php'; ?>
