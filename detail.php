<?php
session_start();
if (file_exists('config/database.php')) { require_once 'config/database.php'; }
if (isset($conn) && !isset($pdo)) { $pdo = $conn; }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null; $all_tags = [];

if (isset($pdo) && $id > 0) {
    try {
        $all_tags = $pdo->query("SELECT DISTINCT tag FROM products WHERE tag IS NOT NULL AND tag != ''")->fetchAll(PDO::FETCH_COLUMN);
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1"); 
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}
}
if (!$product) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | TZY STORE</title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #fff; color: #1a1a1a; font-family: 'Plus Jakarta Sans', sans-serif; padding-top: 140px; }
        .tzy-navbar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #f2f2f2; }
        .nav-link { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.1em; color: #7a7a7a !important; padding: 10px 15px !important; }
        .nav-link.active, .nav-link:hover { color: #1a1a1a !important; }
        
        .product-img { width: 100%; max-width: 500px; border-radius: 12px; object-fit: cover; background: #f8f8fa; }
        
        /* CSS NÚT BẤM LỰA CHỌN SIZE & GIỚI TÍNH */
        .selector-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #7a7a7a; margin-bottom: 8px; }
        .btn-option { border: 1px solid #e5e5e5; background: #fff; color: #1a1a1a; padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 4px; transition: 0.2s; cursor: pointer; }
        .btn-option:hover { border-color: #1a1a1a; }
        .btn-check:checked + .btn-option { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
        
        .btn-qty { border: 1px solid #e5e5e5; background: #fff; padding: 6px 14px; border-radius: 4px; }
        .btn-submit { background: #1a1a1a; color: #fff; border: none; padding: 14px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; transition: 0.3s; }
        .btn-submit:hover { background: #333; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg tzy-navbar fixed-top py-3">
        <div class="container justify-content-between">
            <a class="navbar-brand fw-bold" href="index.php" style="letter-spacing:0.2em; font-size:16px; color:#1a1a1a;">TZY STORE</a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav gap-1">
                    <li class="nav-item"><a class="nav-link" href="index.php">TẤT CẢ</a></li>
                    <?php foreach ($all_tags as $t): ?>
                        <li class="nav-item"><a class="nav-link <?php echo $product['tag']===$t?'active':''; ?>" href="index.php?tag=<?php echo urlencode($t); ?>"><?php echo htmlspecialchars($t); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="cart.php" class="text-dark fs-5"><i class="bi bi-bag"></i></a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-md-6 text-center">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" class="product-img" onerror="this.src='images/default.jpg'">
            </div>
            
            <div class="col-md-6">
                <span class="text-muted text-uppercase small" style="letter-spacing:0.1em; font-size:11px;"><?php echo htmlspecialchars($product['tag']); ?></span>
                <h1 class="fw-bold my-2" style="font-size:28px; letter-spacing:-0.02em;"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="fs-4 fw-bold text-danger mb-4"><?php echo number_format((float)$product['price'], 0, ',', '.'); ?> đ</div>
                
                <p class="text-muted small mb-4" style="line-height:1.7; font-size:13px;">
                    <?php echo !empty($product['description']) ? htmlspecialchars($product['description']) : 'Sản phẩm sở hữu đường may tỉ mỉ, chất liệu thoáng mát, thuộc bộ sưu tập cao cấp mới nhất tại hệ thống TZY Store.'; ?>
                </p>
                
                <form action="add_to_cart.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    
                    <div class="mb-3">
                        <div class="selector-label">Phân loại giới tính:</div>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="gender" id="g_nam" value="Nam" checked>
                            <label class="btn-option" for="g_nam">ĐỒ NAM</label>

                            <input type="radio" class="btn-check" name="gender" id="g_nu" value="Nữ">
                            <label class="btn-option" for="g_nu">ĐỒ NỮ</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="selector-label">Chọn Kích cỡ (Size):</div>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="size" id="s_s" value="S">
                            <label class="btn-option" for="s_s">S</label>

                            <input type="radio" class="btn-check" name="size" id="s_m" value="M" checked>
                            <label class="btn-option" for="s_m">M</label>

                            <input type="radio" class="btn-check" name="size" id="s_l" value="L">
                            <label class="btn-option" for="s_l">L</label>

                            <input type="radio" class="btn-check" name="size" id="s_xl" value="XL">
                            <label class="btn-option" for="s_xl">XL</label>
                        </div>
                    </div>

                    <div class="selector-label">Số lượng mua:</div>
                    <div class="d-flex align-items-center gap-1 mb-4">
                        <button type="button" class="btn btn-qty" onclick="qtyMod(-1)"><i class="bi bi-dash"></i></button>
                        <input type="text" id="qty-input" name="qty" class="form-control text-center fw-bold bg-white shadow-none" value="1" style="width:55px; border-radius:4px; border-color:#e5e5e5;" readonly>
                        <button type="button" class="btn btn-qty" onclick="qtyMod(1)"><i class="bi bi-plus"></i></button>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 rounded-3">THÊM VÀO GIỎ HÀNG</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function qtyMod(amt) {
            var input = document.getElementById('qty-input');
            var nextVal = parseInt(input.value) + amt;
            if(nextVal >= 1) input.value = nextVal;
        }
    </script>
</body>
</html>