<?php
session_start();

require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<script>alert('Dữ liệu sản phẩm này không tồn tại!'); window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập Nhật Dữ Liệu - Tzy Store</title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <a href="index.php" class="btn btn-secondary mb-3">&larr; Hủy & Quay lại</a>
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0 fw-bold">CẬP NHẬT DỮ LIỆU SẢN PHẨM #<?php echo $product['id']; ?></h5>
            </div>
            <div class="card-body p-4">
                <form id="productForm" action="update.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm *</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá bán hiện tại (đ) *</label>
                            <input type="number" id="price" name="price" class="form-control" required value="<?php echo $product['price']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá gốc cũ (đ)</label>
                            <input type="number" name="old_price" class="form-control" value="<?php echo $product['old_price']; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Thương hiệu *</label>
                            <input type="text" name="tag" class="form-control" required value="<?php echo htmlspecialchars($product['tag']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số lượng kho *</label>
                            <input type="number" id="stock" name="stock" class="form-control" required min="0" value="<?php echo $product['stock']; ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Thay đổi hình ảnh mới (Nếu có)</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                        
                        <div class="mt-2">
                            <span class="small text-muted d-block mb-1">Ảnh hiện tại trong hệ thống:</span>
                            <img src="../<?php echo htmlspecialchars($product['image']); ?>" onerror="this.src='../images/default.jpg'" style="width: 70px; height: 85px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Phân mục</label>
                        <select name="category" class="form-select">
                            <option value="outerwear" <?php if($product['category']=='outerwear') echo 'selected'; ?>>outerwear</option>
                            <option value="T-shirts and polos" <?php if($product['category']=='T-shirts and polos') echo 'selected'; ?>>T-shirts and polos</option>
                            <option value="trousers" <?php if($product['category']=='trousers') echo 'selected'; ?>>trousers</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold py-2">Xác Nhận Lưu Thay Đổi</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>