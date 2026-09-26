<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header("Location: {$url}");
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void
{
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!$sessionToken || !$submittedToken || !hash_equals($sessionToken, $submittedToken)) {
        http_response_code(403);
        exit('Permintaan tidak valid. Silakan kembali dan coba lagi.');
    }
}

function validProductInput(array $input): array
{
    $data = [
        'name' => trim((string) ($input['name'] ?? '')),
        'category' => trim((string) ($input['category'] ?? '')),
        'price' => trim((string) ($input['price'] ?? '')),
        'stock' => trim((string) ($input['stock'] ?? '')),
    ];

    if ($data['category'] === '') {
        $data['category'] = 'Umum';
    }

    $errors = [];
    $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
    $stock = filter_var($data['stock'], FILTER_VALIDATE_INT);

    if ($data['name'] === '') {
        $errors['name'] = 'Nama produk wajib diisi.';
    } elseif (mb_strlen($data['name']) < 3) {
        $errors['name'] = 'Nama produk minimal 3 karakter.';
    } elseif (mb_strlen($data['name']) > 100) {
        $errors['name'] = 'Nama produk maksimal 100 karakter.';
    }

    if (mb_strlen($data['category']) > 50) {
        $errors['category'] = 'Kategori maksimal 50 karakter.';
    }

    if ($data['price'] === '' || $price === false || $price <= 0) {
        $errors['price'] = 'Harga harus lebih besar dari 0.';
    }

    if ($data['stock'] === '' || $stock === false || $stock < 0) {
        $errors['stock'] = 'Stok harus berupa bilangan bulat minimal 0.';
    }

    $data['price_value'] = $price;
    $data['stock_value'] = $stock;

    return [$data, $errors];
}

function validProductId(mixed $value): ?int
{
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}
