<?php
// Nhúng file kết nối
require_once 'config/database.php';

// Thử thực hiện một truy vấn đơn giản
try {
    $stmt = $pdo->query("SELECT 1");
    echo "<h1>Kết nối database thành công!</h1>";
    echo "<p>PDO đã hoạt động tốt.</p>";
} catch (Exception $e) {
    echo "<h1>Lỗi kết nối: " . $e->getMessage() . "</h1>";
}
?>