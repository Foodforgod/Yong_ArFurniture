<?php
require_once '../includes/db.php';
require_once '../includes/categories.php';
require_once '../includes/admin_layout.php';

$id = $_GET['id'] ?? null;
$product = [
    'category_id' => '', 'slug' => '', 'name' => '', 'description' => '',
    'price' => '', 'currency' => 'RM', 'glb_path' => '', 'usdz_path' => '',
    'thumb_path' => '', 'width_cm' => '', 'height_cm' => '', 'depth_cm' => '',
    'is_active' => 1, 'sort_order' => 0
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if ($existing) {
        $product = $existing;
    } else {
        echo "<div class='alert alert-danger'>Product not found.</div>";
        include '../includes/admin_footer.php';
        exit;
    }
}

$categories = getAllCategories();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }
    $category_id = $_POST['category_id'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? 0;
    $currency = trim($_POST['currency'] ?? 'RM');
    $width_cm = $_POST['width_cm'] ?? 0;
    $height_cm = $_POST['height_cm'] ?? 0;
    $depth_cm = $_POST['depth_cm'] ?? 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'] ?? 0;

    $glb_path = $product['glb_path'];
    $usdz_path = $product['usdz_path'];
    $thumb_path = $product['thumb_path'];

    // Handle GLB Upload
    if (isset($_FILES['glb_file']) && $_FILES['glb_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['glb_file']['name'], PATHINFO_EXTENSION));
        if ($ext === 'glb') {
            $filename = bin2hex(random_bytes(8)) . '.glb';
            $destination = '../models/' . $filename;
            if (move_uploaded_file($_FILES['glb_file']['tmp_name'], $destination)) {
                $glb_path = 'models/' . $filename;
            }
        } else {
            $error = 'Invalid 3D model format. Only .glb files are accepted.';
        }
    }

    // Handle USDZ Upload
    if (isset($_FILES['usdz_file']) && $_FILES['usdz_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['usdz_file']['name'], PATHINFO_EXTENSION));
        if ($ext === 'usdz') {
            $filename = bin2hex(random_bytes(8)) . '.usdz';
            $destination = '../models/' . $filename;
            if (move_uploaded_file($_FILES['usdz_file']['tmp_name'], $destination)) {
                $usdz_path = 'models/' . $filename;
            }
        }
    }

    // Handle Thumbnail Upload
    if (isset($_FILES['thumb_file']) && $_FILES['thumb_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['thumb_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $filename = bin2hex(random_bytes(8)) . '.' . $ext;
            $destination = '../uploads/thumbs/' . $filename;
            if (move_uploaded_file($_FILES['thumb_file']['tmp_name'], $destination)) {
                $thumb_path = 'uploads/thumbs/' . $filename;
            }
        } else {
            $error = 'Invalid thumbnail image format.';
        }
    }

    if (empty($error) && !empty($glb_path)) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE products SET category_id = ?, slug = ?, name = ?, description = ?, price = ?, currency = ?, glb_path = ?, usdz_path = ?, thumb_path = ?, width_cm = ?, height_cm = ?, depth_cm = ?, is_active = ?, sort_order = ? WHERE id = ?");
            $stmt->execute([$category_id, $slug, $name, $description, $price, $currency, $glb_path, $usdz_path, $thumb_path, $width_cm, $height_cm, $depth_cm, $is_active, $sort_order, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, slug, name, description, price, currency, glb_path, usdz_path, thumb_path, width_cm, height_cm, depth_cm, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $slug, $name, $description, $price, $currency, $glb_path, $usdz_path, $thumb_path, $width_cm, $height_cm, $depth_cm, $is_active, $sort_order]);
        }
        header('Location: index.php');
        exit;
    } elseif (empty($glb_path)) {
        $error = 'A GLB 3D model file is required.';
    }
}
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4"><?= $id ? 'Edit Product' : 'Add New Product' ?></h3>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug (URL identifier)</label>
                        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($product['slug']) ?>" placeholder="Leave blank to auto-generate">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select category...</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Currency</label>
                            <input type="text" name="currency" class="form-control" value="<?= htmlspecialchars($product['currency']) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Width (cm)</label>
                            <input type="number" step="0.01" name="width_cm" class="form-control" value="<?= htmlspecialchars($product['width_cm']) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Height (cm)</label>
                            <input type="number" step="0.01" name="height_cm" class="form-control" value="<?= htmlspecialchars($product['height_cm']) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Depth (cm)</label>
                            <input type="number" step="0.01" name="depth_cm" class="form-control" value="<?= htmlspecialchars($product['depth_cm']) ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">GLB Model File</label>
                            <input type="file" name="glb_file" class="form-control" accept=".glb">
                            <?php if ($product['glb_path']): ?><small class="text-muted">Current: <?= htmlspecialchars($product['glb_path']) ?></small><?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">USDZ Model File (iOS)</label>
                            <input type="file" name="usdz_file" class="form-control" accept=".usdz">
                            <?php if ($product['usdz_path']): ?><small class="text-muted">Current: <?= htmlspecialchars($product['usdz_path']) ?></small><?php endif; ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Thumbnail Image</label>
                            <input type="file" name="thumb_file" class="form-control" accept="image/*">
                            <?php if ($product['thumb_path']): ?><small class="text-muted">Current: <?= htmlspecialchars($product['thumb_path']) ?></small><?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($product['sort_order']) ?>">
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1" <?= $product['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label fw-semibold" for="isActiveCheck">Active (Visible on storefront)</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2">Save Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/admin_footer.php'; ?>