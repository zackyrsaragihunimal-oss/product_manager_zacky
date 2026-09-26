<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Edit Produk';
$id = validProductId($_GET['id'] ?? $_POST['id'] ?? null);

if ($id === null) {
    flash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

$statement = $pdo->prepare('SELECT id, name, category, price, stock FROM products WHERE id = :id');
$statement->execute(['id' => $id]);
$product = $statement->fetch();

if (!$product) {
    flash('error', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$data = ['name' => $product['name'], 'category' => $product['category'], 'price' => $product['price'], 'stock' => $product['stock']];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    [$data, $errors] = validProductInput($_POST);

    if (!$errors) {
        try {
            $statement = $pdo->prepare(
                'UPDATE products SET name = :name, category = :category, price = :price, stock = :stock WHERE id = :id'
            );
            $statement->execute([
                'name' => $data['name'],
                'category' => $data['category'],
                'price' => $data['price_value'],
                'stock' => $data['stock_value'],
                'id' => $id,
            ]);
            flash('success', 'Produk berhasil diperbarui.');
            redirect('index.php');
        } catch (PDOException $e) {
            $errors['name'] = $e->getCode() === '23000' ? 'Nama produk sudah digunakan.' : 'Produk gagal diperbarui.';
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>
<main>
    <section class="page-intro"><p class="eyebrow">PRODUCT MANAGER</p><h1>Edit Produk</h1><p>Perbarui informasi produk yang dipilih.</p></section>
    <section class="form-panel">
        <form method="POST" action="edit.php?id=<?= e($id) ?>" novalidate>
            <input type="hidden" name="id" value="<?= e($id) ?>">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <?php require __DIR__ . '/../includes/product-fields.php'; ?>
            <div class="form-actions"><a class="button button-light" href="index.php">Batal</a><button class="button button-green" type="submit">Simpan Perubahan</button></div>
        </form>
    </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
