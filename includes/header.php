<?php
require_once __DIR__ . "/../config/app.php";

$pageTitle = $pageTitle ?? "Aqua Galaxy | Ornamental Fish & Ladies Tailoring";
$currentScript = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Aqua Galaxy - Premium ornamental fish, aquarium essentials, water products, and bespoke ladies' tailoring services in Thivim, North Goa.">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Tailwind CSS (Async non-blocking loader so offline/slow CDN never freezes the page) -->
    <script>
        window.tailwind = {
            config: {
                theme: {
                    extend: {
                        colors: {
                            ocean: {
                                50: '#f0f9ff',
                                100: '#e0f2fe',
                                200: '#bae6fd',
                                300: '#7dd3fc',
                                400: '#38bdf8',
                                500: '#0ea5e9',
                                600: '#0284c7',
                                700: '#0369a1',
                                800: '#075985',
                                900: '#0c4a6e',
                                950: '#06264d',
                            }
                        }
                    }
                }
            }
        };
    </script>
    <script src="https://cdn.tailwindcss.com" async></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 antialiased pb-20 md:pb-0">