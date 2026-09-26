<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

verifyCsrf();
$id = validProductId($_POST['id'] ?? null);

if ($id === null) {
    flash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

$statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
$statement->execute(['id' => $id]);

flash($statement->rowCount() ? 'success' : 'error', $statement->rowCount() ? 'Produk berhasil dihapus.' : 'Produk tidak ditemukan.');
redirect('index.php');
