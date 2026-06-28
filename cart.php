<?php
session_start();
if (file_exists('config/database.php')) { require_once 'config/database.php'; }
if (isset($conn) && !isset($pdo)) { $pdo = $conn; }

if (isset($_POST['login_email'])) { $_SESSION['user_email'] = trim($_POST['login_email']); header("Location: cart.php"); exit; }
if (isset($_GET['action']) && $_GET['action'] == 'logout') { unset($_SESSION['user_email']); header("Location: cart.php"); exit; }

$is_logged = isset($_SESSION['user_email']);
$email = $is_logged ? $_SESSION['user_email'] : '';
$history = []; $cart_items = []; $total = 0;

if (isset($pdo)) {
    if ($is_logged) {
        try {
            $st = $pdo->prepare("SELECT * FROM orders WHERE user_email = ? ORDER BY id DESC"); $st->execute([$email]);
            $history = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e){}
    }
    if (!empty($_SESSION['cart'])) {
        try {
            $ids = array_keys($_SESSION['cart']);
            $p = implode(',', array_fill(0, count($ids), '?'));
            $st = $pdo->prepare("SELECT * FROM products WHERE id IN ($p)"); $st->execute($ids);
            $cart_items = $st->fetchAll(PDO::FETCH_ASSOC);
            foreach($cart_items as $i) { $total += $i['price'] * $_SESSION['cart'][$i['id']]; }
        } catch(Exception $e){}
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng TZY</title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light py-5" style="font-family:'Plus Jakarta Sans'; font-size:13px;">

    <nav class="navbar navbar-light bg-white border-bottom fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php" style="letter-spacing:0.2em;">TZY STORE</a>
            <a href="index.php" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="font-size:11px; font-weight:600;"><i class="bi bi-arrow-left"></i> Quay lại mua sắm</a>
        </div>
    </nav>

    <div class="container mt-5 pt-3">
        <div class="row g-4">
            <div class="col-md-7">
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold text-uppercase mb-3"><i class="bi bi-bag-check me-2"></i>Giỏ hàng của ní</h6>
                    <?php if(empty($cart_items)): ?><p class="text-muted my-3">Giỏ hàng trống.</p><?php else: ?>
                        <?php foreach($cart_items as $item): $q = $_SESSION['cart'][$item['id']]; ?>
                            <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" onerror="this.src='images/default.jpg'" style="width:50px; height:65px; object-fit:cover; border-radius:4px;">
                                    <div><div class="fw-semibold"><?php echo htmlspecialchars($item['name']); ?></div><div class="text-muted small"><?php echo number_format($item['price'],0,',','.'); ?> đ</div></div>
                                </div>
                                <div class="text-end"><div class="fw-bold">x<?php echo $q; ?></div><div class="text-muted small"><?php echo number_format($item['price']*$q,0,',','.'); ?> đ</div></div>
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between mt-3 fw-bold fs-5"><span>Tổng tiền:</span><span class="text-danger"><?php echo number_format($total,0,',','.'); ?> đ</span></div>
                    <?php endif; ?>
                </div>

                <div class="bg-white border rounded-3 p-4">
                    <h6 class="fw-bold text-uppercase mb-3"><i class="bi bi-clock-history me-2"></i>Lịch sử đặt hàng</h6>
                    <div class="table-responsive"><table class="table table-sm align-middle m-0 small">
                        <thead><tr><th>Mã đơn</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Trạng thái</th></tr></thead>
                        <tbody>
                            <?php if(!empty($history)): foreach($history as $h): ?>
                                <tr><td><strong>#TZY<?php echo $h['id']; ?></strong></td><td><?php echo $h['created_at']; ?></td><td class="text-danger fw-bold"><?php echo number_format($h['total_amount'],0,',','.'); ?> đ</td><td><span class="badge bg-dark"><?php echo htmlspecialchars($h['status']); ?></span></td></tr>
                            <?php endforeach; else: ?><tr><td colspan="4" class="text-muted py-2">Chưa có đơn hàng nào.</td></tr><?php endif; ?>
                        </tbody>
                    </table></div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="bg-white border rounded-3 p-4 mb-4">
                    <h6 class="fw-bold text-uppercase mb-3"><i class="bi bi-person me-2"></i>Tra cứu Email</h6>
                    <?php if(!$is_logged): ?>
                        <form action="cart.php" method="POST">
                            <input type="email" name="login_email" class="form-control mb-2" placeholder="nhap-email@gmail.com" required style="font-size:13px;">
                            <button type="submit" class="btn btn-dark w-100 py-2 text-uppercase fw-bold" style="font-size:11px;">Xác thực tài khoản</button>
                        </form>
                    <?php else: ?>
                        <div class="d-flex justify-content-between align-items-center"><span>Email: <strong class="text-success"><?php echo htmlspecialchars($email); ?></strong></span><a href="cart.php?action=logout" class="btn btn-sm btn-outline-danger">Đăng xuất</a></div>
                    <?php endif; ?>
                </div>

                <div class="bg-white border rounded-3 p-4">
                    <h6 class="fw-bold text-uppercase mb-3"><i class="bi bi-credit-card me-2"></i>Thông tin giao hàng</h6>
                    <?php if(empty($cart_items)): ?><p class="text-muted m-0">Vui lòng thêm sản phẩm vào túi hàng.</p>
                    <?php elseif(!$is_logged): ?><p class="text-danger m-0">Vui lòng xác thực Email ở trên để tiếp tục đặt mua hàng.</p>
                    <?php else: ?>
                        <form action="checkout_process.php" method="POST">
                            <input type="text" name="fullname" class="form-control mb-2" placeholder="Họ tên người nhận..." required style="font-size:13px;">
                            <input type="tel" name="phone" class="form-control mb-2" placeholder="Số điện thoại..." required style="font-size:13px;">
                            <input type="text" name="address" class="form-control mb-3" placeholder="Địa chỉ giao hàng..." required style="font-size:13px;">
                            <button type="submit" class="btn btn-dark w-100 py-3 text-uppercase fw-bold" style="font-size:11px;">Xác nhận đặt đơn (COD)</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>