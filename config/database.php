<?php
// Tránh việc nhúng trùng lặp session nếu cấu hình ở nhiều nơi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'tzy_store'; 
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Báo lỗi dạng Exception dễ debug
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Trả về dữ liệu dạng mảng key => value
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Tăng tính bảo mật chống SQL Injection
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     // Tạo thêm biến $conn gán bằng $pdo để nếu code cũ của bạn có dùng $conn thì vẫn chạy được
     $conn = $pdo; 
} catch (\PDOException $e) {
     // Khuyên dùng: In ra lỗi rõ ràng để bạn dễ sửa khi cấu hình sai user/pass/database
     die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>