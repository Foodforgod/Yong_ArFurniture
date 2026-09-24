<?php
require_once 'includes/db.php';
require_once 'includes/categories.php';
require_once 'includes/products.php';
$config = require_once 'config/config.php';

$category_slug = $_GET['category'] ?? null;
$selected_category = null;
$categories = getActiveCategories();
if (!is_array($categories)) {
    $categories = [];
}

$products = [];
if ($category_slug) {
    $selected_category = getCategoryBySlug($category_slug);
    if ($selected_category && isset($selected_category['id'])) {
        $products = getActiveProducts($selected_category['id']);
    } else {
        $products = getActiveProducts();
    }
} else {
    $products = getActiveProducts();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['app']['name'] ?? 'AR Furniture') ?> - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-cube me-2"></i><?= htmlspecialchars($config['app']['name'] ?? 'AR Furniture') ?></a>
            <div class="ms-auto">
                <a href="admin/login.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-user-gear me-1"></i> Admin</a>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-5 fw-bold">See It Before You Buy</h1>
            <p class="lead col-lg-8 mx-auto">Preview premium furniture in your actual room scale using Augmented Reality directly from your mobile device.</p>
            <div class="d-none d-lg-block mt-4">
                <div id="qrcode" class="d-inline-block bg-white p-2 rounded"></div>
                <p class="small text-white-50 mt-2">Scan with your phone to test AR instantly</p>
            </div>
        </div>
    </header>

    <main class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    <a href="index.php" class="btn <?= empty($category_slug) ? 'btn-dark' : 'btn-outline-dark' ?>">All Categories</a>
                    <?php foreach ($categories as $cat): ?>
                        <?php if (is_array($cat) && isset($cat['slug'], $cat['name'])): ?>
                            <a href="index.php?category=<?= htmlspecialchars($cat['slug']) ?>" class="btn <?= ($category_slug === $cat['slug']) ? 'btn-dark' : 'btn-outline-dark' ?>"><?= htmlspecialchars($cat['name']) ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No furniture products found in this category.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $prod): ?>
                    <div class="col">
                        <div class="card product-card h-100 shadow-sm">
                            <img src="<?= htmlspecialchars($prod['thumb_path'] ?? 'uploads/thumbs/placeholder.jpg') ?>" class="card-img-top product-thumb" alt="<?= htmlspecialchars($prod['name'] ?? '') ?>" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-secondary align-self-start mb-2"><?= htmlspecialchars($prod['category_name'] ?? 'General') ?></span>
                                <h5 class="card-title"><?= htmlspecialchars($prod['name'] ?? '') ?></h5>
                                <p class="card-text text-primary fw-bold fs-5 mb-3"><?= formatPrice($prod['price'] ?? 0, $prod['currency'] ?? 'USD') ?></p>
                                <a href="product.php?slug=<?= htmlspecialchars($prod['slug'] ?? '') ?>" class="btn btn-dark mt-auto">View in your space &rarr;</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app']['name'] ?? 'AR Furniture') ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/qr.js"></script>
</body>
</html>