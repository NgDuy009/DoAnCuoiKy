<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 0);
error_reporting(0);

if (file_exists('config/database.php')) {
    require_once 'config/database.php';
} elseif (file_exists('../config/database.php')) {
    require_once '../config/database.php';
}
if (isset($conn) && !isset($pdo)) { $pdo = $conn; }

// Nếu không có dữ liệu giỏ hàng hoặc người dùng chưa đăng nhập, đá về trang chủ
if (empty($_SESSION['cart']) || !isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

// Lấy thông tin từ form gửi lên
$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$user_email = $_SESSION['user_email'];

if (empty($fullname) || empty($phone) || empty($address)) {
    echo "<script>alert('Vui lòng điền đầy đủ thông tin giao hàng!'); window.history.back();</script>";
    exit;
}

// 1. Tính tổng tiền giỏ hàng thực tế
$total_money = 0;
try {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt_cart = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt_cart->execute(array_keys($_SESSION['cart']));
    $products_in_cart = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products_in_cart as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $total_money += $p['price'] * $qty;
    }

    // 2. TỰ ĐỘNG TẠO BẢNG ORDERS NẾU CHƯA CÓ (Chống sập database tuyệt đối cho ní luôn)
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_email VARCHAR(255) NOT NULL,
        fullname VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        total_amount DECIMAL(15,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'Đang xử lý',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. Tiến hành chèn đơn hàng mới vào Database
    $sql_insert = "INSERT INTO orders (user_email, fullname, phone, address, total_amount, status) VALUES (?, ?, ?, ?, ?, 'Đang xử lý')";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([$user_email, $fullname, $phone, $address, $total_money]);

    // Đặt hàng thành công -> Xóa giỏ hàng hiện tại
    $_SESSION['cart'] = [];

    // Hiển thị thông báo đẹp đẽ và quay về trang chủ
    echo "<script>
        alert('🎉 Chúc mừng ní đã đặt hàng thành công đơn hàng Luxury của TZY Store!');
        window.location.href = 'index.php';
    </script>";
    exit;

} catch (Exception $e) {
    echo "Lỗi hệ thống đặt hàng: " . $e->getMessage();
}