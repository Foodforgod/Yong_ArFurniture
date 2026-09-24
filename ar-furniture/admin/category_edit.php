<?php
require_once '../includes/db.php';
require_once '../includes/admin_layout.php';

$id = $_GET['id'] ?? null;
$category = ['name' => '', 'slug' => '', 'sort_order' => 0, 'is_active' => 1];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if ($existing) {
        $category = $existing;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }
    $sort_order = $_POST['sort_order'] ?? 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($name)) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, sort_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $sort_order, $is_active, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, sort_order, is_active) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $sort_order, $is_active]);
        }
        header('Location: categories.php');
        exit;
    } else {
        $error = 'Category name is required.';
    }
}
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4"><?= $id ? 'Edit Category' : 'Add New Category' ?></h3>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($category['slug']) ?>" placeholder="Leave blank to auto-generate">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= htmlspecialchars($category['sort_order']) ?>">
                    </div>
                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="catActive" value="1" <?= $category['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="catActive">Active</label>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2">Save Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once '../includes/admin_footer.php'; ?>