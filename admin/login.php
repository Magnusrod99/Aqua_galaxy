
<?php
session_start();

// Redirect an already logged-in admin
if (isset($_SESSION["admin_id"])) {
    require_once __DIR__ . "/../config/app.php";
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
}

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
        $error = "Invalid email or password.";
    } else {
        try {
            $stmt = $pdo->prepare(
                "SELECT id, name, email, password, status
                 FROM admins
                 WHERE email = :email
                 LIMIT 1"
            );

            $stmt->execute(["email" => $email]);
            $admin = $stmt->fetch();

          if (!$admin) {
    $error = "DEBUG: Admin email not found in database.";
} elseif ((int) $admin["status"] !== 1) {
    $error = "DEBUG: Admin status is not 1.";
} elseif (!password_verify($password, $admin["password"])) {
    $error = "DEBUG: Password does not match the stored hash.";
} else {
    session_regenerate_id(true);

    $_SESSION["admin_id"] = (int) $admin["id"];
    $_SESSION["admin_name"] = $admin["name"];
    $_SESSION["admin_email"] = $admin["email"];

    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
} {
                session_regenerate_id(true);

                $_SESSION["admin_id"] = (int) $admin["id"];
                $_SESSION["admin_name"] = $admin["name"];
                $_SESSION["admin_email"] = $admin["email"];

                header("Location: " . BASE_URL . "/admin/dashboard.php");
                exit;
            }

            $error = "Invalid email or password.";
        } catch (PDOException $e) {
            error_log("Admin login database error: " . $e->getMessage());
            $error = "Login is temporarily unavailable. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Aqua Galaxy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">

    <main class="w-full max-w-md">

        <div class="text-center mb-8">
            <a href="<?= BASE_URL ?>/" class="inline-flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-sky-100 border-2 border-sky-300 flex items-center justify-center text-3xl">
                    🐟
                </div>

                <span class="font-bold text-3xl text-slate-900">
                    Aqua <span class="text-sky-500">Galaxy</span>
                </span>
            </a>

            <p class="text-slate-500 mt-3">Administration Portal</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 sm:p-8">

            <h1 class="text-2xl font-bold text-slate-900">Admin Login</h1>

            <p class="text-slate-500 text-sm mt-2 mb-6">
                Sign in to manage your website.
            </p>

            <?php if ($error !== ""): ?>
                <div role="alert" class="bg-red-50 text-red-700 p-3 rounded-lg mb-5 text-sm">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="admin@example.com"
                        autocomplete="username"
                        maxlength="254"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 rounded-lg transition duration-200"
                >
                    Sign In
                </button>

            </form>

            <div class="border-t border-slate-100 mt-6 pt-5 text-center">
                <a href="<?= BASE_URL ?>/" class="text-sm text-sky-600 hover:text-sky-700">
                    ← Back to Website
                </a>
            </div>

        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            © <?= date("Y") ?> Aqua Galaxy. All rights reserved.
        </p>

    </main>

</body>
</html>
