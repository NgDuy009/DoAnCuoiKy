<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_logged'])) {
    header('Location: login.php');
    exit;
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

$stmt_order = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt_order->execute([$order_id, $user_id]);
$order = $stmt_order->fetch();

if (!$order) {
    echo "<script>alert('Đơn hàng không tồn tại hoặc bạn không có quyền xem!'); window.location.href='orders.php';</script>";
    exit;
}

$stmt_items = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn Điện Tử #<?php echo $order['id']; ?> - Tzy Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invoice-box { background: #fff; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); }
        @media print { .btn-print { display: none !important; } } /* Ẩn nút khi bấm in */
    </style>
</head>
<body class="bg-light">
<div class="container my-5" style="max-width: 800px;">
    
    <div class="d-flex justify-content-between mb-3 btn-print">
        <a href="orders.php" class="btn btn-secondary rounded-0">&larr; Trở lại đơn hàng của tôi</a>
        <button onclick="window.print()" class="btn btn-dark rounded-0 fw-bold px-4">IN HÓA ĐƠN</button>
    </div>

    <div class="invoice-box">
        <div class="row mb-4">
            <div class="col-6">
                <h2 class="fw-bold m-0">TZY STORE</h2>
                <small class="text-muted">Hệ thống thời trang cao cấp</small>
            </div>
            <div class="col-6 text-end">
                <h4 class="fw-bold m-0 text-uppercase">Hóa Đơn Bán Hàng</h4>
                <p class="text-muted m-0">Mã đơn: <strong>#<?php echo $order['id']; ?></strong></p>
                <small class="text-muted">Ngày lập: <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></small>
            </div>
        </div>
        
        <hr>

        <div class="row my-4">
            <div class="col-12">
                <h6 class="text-uppercase fw-bold text-muted small">Thông tin người nhận hàng:</h6>
                <p class="m-0">Họ và tên: <strong><?php echo htmlspecialchars($order['fullname']); ?></strong></p>
                <p class="m-0">Số điện thoại: <?php echo htmlspecialchars($order['phone']); ?></p>
                <p class="m-0">Địa chỉ giao hàng: <?php echo htmlspecialchars($order['address']); ?></p>
            </div>
        </div>

        <table class="table table-bordered align-middle mt-4">
            <thead class="table-light small text-uppercase text-muted">
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center" style="width: 100px;">Số lượng</th>
                    <th class="text-end" style="width: 150px;">Đơn giá</th>
                    <th class="text-end" style="width: 150px;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="public/<?php echo $item['image']; ?>" width="40" height="50" class="me-2" style="object-fit: cover;">
                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                        </div>
                    </td>
                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                    <td class="text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
                    <td class="text-end fw-semibold"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ</td>
                </tr>
                <?php endforeach; ?>
                
                <tr>
                    <td colspan="3" class="text-end fw-bold text-uppercase small">Tổng thanh toán:</td>
                    <td class="text-end fw-bold text-danger fs-5"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</td>
                </tr>
            </tbody>
        </table>
        
        <div class="text-center mt-5">
            <p class="fst-italic text-muted small">Cảm ơn bạn đã mua sắm tại Tzy Store!</p>
        </div>
    </div>
</div>
</body>
</html>