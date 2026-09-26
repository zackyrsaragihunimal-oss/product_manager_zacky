<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Tambah Produk';
$data = ['name' => '', 'category' => 'Umum', 'price' => '', 'stock' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    [$data, $errors] = validProductInput($_POST);

    if (!$errors) {
        try {
            $statement = $pdo->prepare(
                'INSERT INTO products (name, category, price, stock)
                 VALUES (:name, :category, :price, :stock)'
            );
            $statement->execute([
                'name' => $data['name'],
                'category' => $data['category'],
                'price' => $data['price_value'],
                'stock' => $data['stock_value'],
            ]);
            flash('success', 'Produk berhasil ditambahkan.');
            redirect('index.php');
        } catch (PDOException $e) {
            $errors['name'] = $e->getCode() === '23000' ? 'Nama produk sudah digunakan.' : 'Produk gagal disimpan.';
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>
<main>
    <section class="page-intro"><p class="eyebrow">PRODUCT MANAGER</p><h1>Tambah Produk</h1><p>Masukkan detail produk baru ke dalam inventaris.</p></section>
    <section class="form-panel">
        <form method="POST" action="create.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <?php require __DIR__ . '/../includes/product-fields.php'; ?>
            <div class="form-actions"><a class="button button-light" href="index.php">Batal</a><button class="button button-green" type="submit">Simpan Produk</button></div>
        </form>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
