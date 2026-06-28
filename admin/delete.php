<?php
// Kiến thức: PHP & PDO - Thực thi lệnh DELETE bản ghi
session_start();
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        echo "<script>alert('Đã xóa sản phẩm khỏi cơ sở dữ liệu thành công!'); window.location.href='index.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Thất bại: Không thể xóa dòng sản phẩm này do ràng buộc dữ liệu!'); window.location.href='index.php';</script>";
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}