<?php
session_start();
require_once 'config/database.php'; 

if (!isset($_SESSION['user_logged'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; 

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn Hàng Của Tôi - Tzy Store</title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h3 class="fw-bold mb-4">LỊCH SỬ ĐƠN HÀNG ĐÃ ĐẶT</h3>
    
    <div class="card border-0 shadow-sm rounded-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-dark text-uppercase small">
                        <tr>
                            <th class="ps-3">Mã Đơn</th>
                            <th>Ngày đặt</th>
                            <th>Người nhận</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="ps-3 fw-bold">#<?php echo $order['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                                <td class="text-danger fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</td>
                                <td>
                                    <?php 
                                    if ($order['status'] == 'pending') echo '<span class="badge bg-warning text-dark rounded-0">Chờ xử lý</span>';
                                    elseif ($order['status'] == 'completed') echo '<span class="badge bg-success rounded-0">Thành công</span>';
                                    else echo '<span class="badge bg-danger rounded-0">Đã hủy</span>';
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-dark rounded-0 px-3">Xem hóa đơn</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Bạn chưa đặt đơn hàng nào tại hệ thống.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>