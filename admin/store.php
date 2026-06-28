<?php
// admin/store.php
ini_set('display_errors', 0);
error_reporting(0);
require_once dirname(__DIR__) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $old_price = $_POST['old_price'];
    $tag = trim($_POST['tag']);
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    // Server-side Validation
    if (empty($name) || empty($price) || $price <= 0) {
        echo "<script>alert('Vui lòng nhập thông tin hợp lệ!'); window.history.back();</script>";
        exit;
    }

    $image_db_path = 'images/default.jpg'; // Ảnh mặc định nếu lỗi tệp

    // XỬ LÝ FILE UPLOAD THỰC TẾ
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['image_file']['name']);
        $target_dir = "../images/";

        // Nếu chưa có thư mục images thì tạo tự động
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $target_dir . $file_name)) {
            $image_db_path = 'images/' . $file_name;
        }
    }

    try {
        $sql = "INSERT INTO products (name, price, old_price, image, tag, category, stock) 
                VALUES (:name, :price, :old_price, :image, :tag, :category, :stock)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':old_price' => $old_price,
            ':image' => $image_db_path,
            ':tag' => $tag,
            ':category' => $category,
            ':stock' => $stock
        ]);

        echo "<script>alert('Thêm sản phẩm mới thành công!'); window.location.href='index.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi hệ thống không thể lưu sản phẩm!'); window.history.back();</script>";
        exit;
    }
}
?>