<?php
require_once '../includes/db.php';
require_once '../includes/categories.php';
require_once '../includes/admin_layout.php';

$categories = getAllCategories();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Categories</h2>
    <a href="category_edit.php" class="btn btn-dark"><i class="fa-solid fa-plus me-1"></i> Add Category</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></td>
                        <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                        <td><?= htmlspecialchars($cat['sort_order']) ?></td>
                        <td>
                            <span class="badge bg-<?= $cat['is_active'] ? 'success' : 'secondary' ?>">
                                <?= $cat['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <a href="category_edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                            <a href="category_delete.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deleting category will fail if products are linked. Proceed?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/admin_footer.php'; ?>