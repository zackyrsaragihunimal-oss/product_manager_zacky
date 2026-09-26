<?php
$pageTitle = $pageTitle ?? 'Product Manager';
$flashMessage = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="site-shell">
        <header class="site-header">
            <a class="brand" href="index.php">
                <span class="brand-mark">PM</span>
                <span>Product Manager</span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="index.php">Dashboard</a>
                <a class="nav-action" href="create.php">+ Tambah Produk</a>
            </nav>
        </header>

        <?php if ($flashMessage): ?>
            <div class="alert alert-<?= e($flashMessage['type']) ?>">
                <?= e($flashMessage['message']) ?>
            </div>
        <?php endif; ?>
