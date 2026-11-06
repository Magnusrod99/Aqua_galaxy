
<?php
require_once __DIR__ . "/../config/app.php";

$pageTitle = $pageTitle ?? "Aqua Galaxy";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="Aqua Galaxy - Aquarium products, ornamental fish, shrimp and ladies tailoring in Thivim, North Goa.">

    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet"
          href="<?= BASE_URL ?>/assets/css/style.css">

</head>

<body>
