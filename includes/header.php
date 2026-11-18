<?php
// includes/header.php  — expects $pageTitle (optional) and $bodyClass (optional)
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/auth.php';
if (empty($publicPage)) { require_login(); }
$pageTitle = $pageTitle ?? 'ITC Ratnadipa | A Luxury Collection Hotel, Colombo';
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= e(rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/') ?>">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= (int)@filemtime(dirname(__DIR__) . '/assets/css/style.css') ?>">
<?php if (!empty($withSplash)): ?>
    <link rel="preload" as="image" href="assets/images/splash.jpg">
    <script>
        try { if (!sessionStorage.getItem('itc_splash')) document.documentElement.classList.add('has-splash'); }
        catch (err) { document.documentElement.classList.add('has-splash'); }
    </script>
<?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>">
<?php if (!empty($withSplash)) { include __DIR__ . '/splash.php'; } ?>
<?php require_once __DIR__ . '/icons.php'; ?>
