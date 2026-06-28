<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Dữ Liệu Mới - Tzy Store</title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <a href="index.php" class="btn btn-secondary mb-3">&larr; Quay lại quản lý dữ liệu</a>
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">THÊM DỮ LIỆU SẢN PHẨM MỚI</h5>
            </div>
            <div class="card-body p-4">
                <form id="productForm" action="store.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Nhập tên quần/áo">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá bán (đ) *</label>
                            <input type="number" id="price" name="price" class="form-control" required placeholder="Ví dụ: 500000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá gốc cũ (đ)</label>
                            <input type="number" name="old_price" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Thương hiệu *</label>
                            <input type="text" name="tag" class="form-control" required placeholder="Ví dụ: DIOR, LV">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số lượng kho *</label>
                            <input type="number" id="stock" name="stock" class="form-control" required min="0" value="10">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tải lên hình ảnh sản phẩm *</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*" required>
                        <small class="text-muted">Chọn tệp ảnh (.png, .jpg, .jpeg) để tải trực tiếp lên server.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Phân mục thiết kế</label>
                        <select name="category" class="form-select">
                            <option value="outerwear">outerwear (Áo khoác ngoài)</option>
                            <option value="T-shirts and polos">T-shirts and polos (Áo phông)</option>
                            <option value="trousers">trousers (Quần)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold py-2">Xác Nhận Thêm Dữ Liệu</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>