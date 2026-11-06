
<?php
session_start();

require_once __DIR__ . "/../config/app.php";

// Verify admin login
if (!isset($_SESSION["admin_id"])) {
    header("Location: " . BASE_URL . "/admin/login.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "/admin/fish.php");
    exit;
}

// Validate the fish ID
$fishId = filter_input(
    INPUT_POST,
    "fish_id",
    FILTER_VALIDATE_INT
);

if (!$fishId || $fishId < 1) {
    header(
        "Location: " . BASE_URL .
        "/admin/fish.php?delete_error=invalid"
    );
    exit;
}
// Verify the CSRF token
$submittedToken = $_POST["csrf_token"] ?? "";
$sessionToken = $_SESSION["csrf_token"] ?? "";

if (
    !is_string($submittedToken) ||
    !is_string($sessionToken) ||
    $sessionToken === "" ||
    !hash_equals($sessionToken, $submittedToken)
) {
    header(
        "Location: " . BASE_URL .
        "/admin/fish.php?delete_error=security"
    );
    exit;
}

// Verify the fish exists before deleting
try {
    $check = $pdo->prepare(
        "SELECT id FROM fish WHERE id = :id LIMIT 1"
    );

    $check->execute(["id" => $fishId]);

    if (!$check->fetch()) {
        header(
            "Location: " . BASE_URL .
            "/admin/fish.php?delete_error=notfound"
        );
        exit;
    }

    // Delete the selected fish
    $delete = $pdo->prepare(
        "DELETE FROM fish WHERE id = :id"
    );

    $delete->execute(["id" => $fishId]);

    header(
        "Location: " . BASE_URL .
        "/admin/fish.php?deleted=1"
    );
    exit;

} catch (PDOException $e) {
    error_log("Fish deletion failed: " . $e->getMessage());

    header(
        "Location: " . BASE_URL .
        "/admin/fish.php?delete_error=failed"
    );
    exit;
}
