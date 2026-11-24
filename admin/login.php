<?php
session_start();
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Please enter a valid email and password.';
    } elseif (!$pdo) {
        $error = 'Database is unavailable. Please ensure XAMPP MySQL is running.';
    } else {
        try {
            $st = $pdo->prepare("SELECT id,name,email,password,status FROM admins WHERE email=:e LIMIT 1");
            $st->execute(['e' => $email]);
            $admin = $st->fetch();

            if ($admin && (int)$admin['status'] === 1 && password_verify($password, $admin['password'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id']    = (int)$admin['id'];
                $_SESSION['admin_name']  = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                header('Location: ' . BASE_URL . '/admin/dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            error_log('Admin login error: ' . $e->getMessage());
            $error = 'Login is temporarily unavailable. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>Admin Login | Aqua Galaxy</title>
<script>window.tailwind={config:{theme:{extend:{colors:{brand:{50:'#f0f9ff',100:'#e0f2fe',400:'#38bdf8',500:'#0ea5e9',600:'#0284c7',700:'#0369a1'}}}}}};</script>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body { background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 50%, #0284c7 100%); }
  .fish-bg { position:fixed; inset:0; overflow:hidden; pointer-events:none; z-index:0; }
  .bubble { position:absolute; border-radius:50%; background:rgba(255,255,255,0.08); animation:rise linear infinite; }
  @keyframes rise { 0%{transform:translateY(100vh) scale(0);opacity:0} 50%{opacity:1} 100%{transform:translateY(-10vh) scale(1);opacity:0} }
</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">

<!-- Bubble animation -->
<div class="fish-bg" aria-hidden="true">
  <?php for($i=0;$i<12;$i++):
    $s=rand(20,80); $l=rand(5,90); $d=rand(5,20); $delay=rand(0,10);
  ?>
  <div class="bubble" style="width:<?=$s?>px;height:<?=$s?>px;left:<?=$l?>%;animation-duration:<?=$d?>s;animation-delay:<?=$delay?>s;"></div>
  <?php endfor; ?>
  <div style="position:absolute;font-size:8rem;bottom:-2rem;right:-2rem;opacity:0.06;transform:rotate(-15deg)">🐟</div>
  <div style="position:absolute;font-size:5rem;top:10%;left:5%;opacity:0.05;">🐠</div>
</div>

<div class="relative z-10 w-full max-w-sm">
  <!-- Logo -->
  <div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-4xl mb-4">
      🐟
    </div>
    <h1 class="text-2xl font-bold text-white">Aqua <span class="text-brand-300">Galaxy</span></h1>
    <p class="text-blue-200 text-sm mt-1">Administration Portal</p>
  </div>

  <!-- Card -->
  <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl px-7 py-8">
    <h2 class="text-xl font-bold text-slate-900 mb-1">Welcome back</h2>
    <p class="text-sm text-slate-500 mb-6">Sign in to manage your website.</p>

    <?php if (!$pdo): ?>
    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 mb-5 text-sm">
      <strong>⚠️ Database Offline</strong><br>
      Start MySQL in XAMPP Control Panel, then try again.
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm" role="alert">
      <?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
        <input type="email" id="email" name="email" required autocomplete="username"
               value="<?= htmlspecialchars($_POST['email']??'',ENT_QUOTES,'UTF-8') ?>"
               class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition"
               placeholder="admin@aquagalaxy.com">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" required autocomplete="current-password"
                 class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition pr-12"
                 placeholder="••••••••">
          <button type="button" onclick="togglePwd()"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition text-lg">
            👁
          </button>
        </div>
      </div>
      <button type="submit"
              class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition text-sm shadow-sm mt-2">
        Sign In →
      </button>
    </form>
  </div>

  <div class="text-center mt-5">
    <a href="<?= BASE_URL ?>/" class="text-blue-200 hover:text-white text-sm transition">← Back to Website</a>
  </div>
  <p class="text-center text-blue-200/60 text-xs mt-4">© <?= date('Y') ?> Aqua Galaxy. All rights reserved.</p>
</div>

<script>
function togglePwd(){
  const p = document.getElementById('password');
  p.type = p.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
