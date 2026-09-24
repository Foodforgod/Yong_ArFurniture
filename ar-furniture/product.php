<?php
require_once 'includes/db.php';
require_once 'includes/products.php';
$config = require_once 'config/config.php';

$slug = $_GET['slug'] ?? '';
$product = getProductBySlug($slug);

if (!$product || !$product['is_active']) {
    http_response_code(404);
    exit("Product not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - AR Viewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Google Model Viewer component -->
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fa-solid fa-arrow-left me-2"></i>Back to Catalog</a>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row g-5">
            <div class="col-lg-7">
                <model-viewer
                    src="<?= htmlspecialchars($product['glb_path']) ?>"
                    <?= !empty($product['usdz_path']) ? 'ios-src="' . htmlspecialchars($product['usdz_path']) . '"' : '' ?>
                    ar
                    ar-modes="webxr scene-viewer quick-look"
                    camera-controls
                    auto-rotate
                    shadow-intensity="1"
                    alt="<?= htmlspecialchars($product['name']) ?>">
                    <button slot="ar-button" class="ar-button">
                        <i class="fa-solid fa-cube me-2"></i>View in your space (AR)
                    </button>
                </model-viewer>
                <div class="alert alert-info mt-3 small">
                    <i class="fa-solid fa-circle-info me-1"></i> AR is supported on compatible mobile devices (Android via Scene Viewer, iOS via Quick Look).
                </div>
            </div>
            <div class="col-lg-5">
                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($product['category_name']) ?></span>
                <h1 class="fw-bold"><?= htmlspecialchars($product['name']) ?></h1>
                <h3 class="text-primary fw-bold mb-3"><?= formatPrice($product['price'], $product['currency']) ?></h3>
                
                <hr>
                
                <h5>Dimensions</h5>
                <ul class="list-unstyled text-muted mb-4">
                    <li>Width: <strong><?= htmlspecialchars($product['width_cm']) ?> cm</strong></li>
                    <li>Height: <strong><?= htmlspecialchars($product['height_cm']) ?> cm</strong></li>
                    <li>Depth: <strong><?= htmlspecialchars($product['depth_cm']) ?> cm</strong></li>
                </ul>

                <h5>Description</h5>
                <p class="text-muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                <div class="card mt-4 bg-light border-0 d-none d-lg-block">
                    <div class="card-body text-center">
                        <p class="small fw-semibold mb-2">Scan to view on your mobile phone in AR:</p>
                        <div id="qrcode" class="d-inline-block"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/qr.js"></script>
</body>
</html>