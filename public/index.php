<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Dashboard';
$query = trim((string) ($_GET['q'] ?? ''));

if ($query !== '') {
    $statement = $pdo->prepare(
        'SELECT id, name, category, price, stock, created_at
         FROM products
         WHERE name LIKE :query OR category LIKE :query
         ORDER BY id DESC'
    );
    $statement->execute(['query' => "%{$query}%"]);
} else {
    $statement = $pdo->query(
        'SELECT id, name, category, price, stock, created_at
         FROM products
         ORDER BY id DESC'
    );
}

$products = $statement->fetchAll();
$summary = $pdo->query(
    'SELECT COUNT(*) AS product_count,
            COALESCE(SUM(stock), 0) AS total_stock,
            COALESCE(SUM(price * stock), 0) AS inventory_value
     FROM products'
)->fetch();

require __DIR__ . '/../includes/header.php';
?>
<main>
    <section class="hero">
        <div>
            <p class="eyebrow">INVENTORY OVERVIEW</p>
            <h1>Kelola produk tanpa ribet.</h1>
            <p class="hero-text">Pantau isi inventaris, perbarui data, dan jaga stok tetap terkendali.</p>
        </div>
        <a class="button button-green" href="create.php">Tambah Produk</a>
    </section>

    <section class="stats-grid" aria-label="Ringkasan inventaris">
        <article class="stat-card">
            <span class="stat-label">Jumlah Produk</span>
            <strong><?= e($summary['product_count']) ?></strong>
            <span class="stat-note">produk tersimpan</span>
        </article>
        <article class="stat-card">
            <span class="stat-label">Total Stok</span>
            <strong><?= e($summary['total_stock']) ?></strong>
            <span class="stat-note">unit tersedia</span>
        </article>
        <article class="stat-card stat-card-accent">
            <span class="stat-label">Nilai Persediaan</span>
            <strong>Rp <?= number_format((float) $summary['inventory_value'], 0, ',', '.') ?></strong>
            <span class="stat-note">harga x stok</span>
        </article>
    </section>

    <section class="content-panel">
        <div class="section-heading">
            <div>
                <p class="eyebrow">PRODUCT LIST</p>
                <h2>Daftar Produk</h2>
            </div>
            <form class="search-form" method="GET" action="index.php">
                <label class="sr-only" for="q">Cari produk</label>
                <input type="search" id="q" name="q" value="<?= e($query) ?>" placeholder="Cari nama atau kategori...">
                <button class="button button-navy" type="submit">Cari</button>
                <?php if ($query !== ''): ?>
                    <a class="button button-light" href="index.php">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (!$products): ?>
            <div class="empty-state">
                <h3><?= $query !== '' ? 'Produk tidak ditemukan' : 'Belum ada produk' ?></h3>
                <p><?= $query !== '' ? 'Coba gunakan kata kunci lain.' : 'Tambahkan produk pertama untuk mulai mengelola inventaris.' ?></p>
                <?php if ($query === ''): ?><a class="button button-green" href="create.php">Tambah Produk</a><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th class="align-right">Tindakan</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td data-label="Produk"><strong><?= e($product['name']) ?></strong><small>ID #<?= e($product['id']) ?></small></td>
                                <td data-label="Kategori"><span class="category-badge"><?= e($product['category']) ?></span></td>
                                <td data-label="Harga">Rp <?= number_format((float) $product['price'], 0, ',', '.') ?></td>
                                <td data-label="Stok"><span class="stock-badge <?= (int) $product['stock'] === 0 ? 'stock-empty' : '' ?>"><?= e($product['stock']) ?> unit</span></td>
                                <td data-label="Tindakan" class="actions align-right">
                                    <a class="action-link" href="edit.php?id=<?= e($product['id']) ?>">Edit</a>
                                    <form method="POST" action="delete.php" onsubmit="return confirm('Hapus produk ini?');">
                                        <input type="hidden" name="id" value="<?= e($product['id']) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                        <button class="action-link danger-link" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
