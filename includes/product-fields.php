<div class="form-grid">
    <div class="form-field form-field-wide">
        <label for="name">Nama Produk</label>
        <input type="text" id="name" name="name" maxlength="100" value="<?= e($data['name']) ?>" required>
        <?php if (isset($errors['name'])): ?><span class="field-error"><?= e($errors['name']) ?></span><?php endif; ?>
    </div>
    <div class="form-field">
        <label for="category">Kategori</label>
        <input type="text" id="category" name="category" maxlength="50" value="<?= e($data['category']) ?>" required>
        <?php if (isset($errors['category'])): ?><span class="field-error"><?= e($errors['category']) ?></span><?php endif; ?>
    </div>
    <div class="form-field">
        <label for="price">Harga (Rp)</label>
        <input type="number" id="price" name="price" min="0.01" step="0.01" value="<?= e($data['price']) ?>" required>
        <?php if (isset($errors['price'])): ?><span class="field-error"><?= e($errors['price']) ?></span><?php endif; ?>
    </div>
    <div class="form-field">
        <label for="stock">Stok</label>
        <input type="number" id="stock" name="stock" min="0" step="1" value="<?= e($data['stock']) ?>" required>
        <?php if (isset($errors['stock'])): ?><span class="field-error"><?= e($errors['stock']) ?></span><?php endif; ?>
    </div>
</div>
