<?php
// admin/update.php
ini_set('display_errors', 0);
error_reporting(0);
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $old_price = $_POST['old_price'];
    $tag = trim($_POST['tag']);
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    if (empty($id) || empty($name) || empty($price) || $price <= 0) {
        echo "<script>alert('Dữ liệu không hợp lệ!'); window.history.back();</script>";
        exit;
    }

    try {
        /** @var PDO $pdo */
        $stmt_current = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt_current->execute([$id]);
        $current_product = $stmt_current->fetch(PDO::FETCH_ASSOC);
        $image_db_path = $current_product['image'] ?? 'images/default.jpg';

        // XỬ LÝ FILE UPLOAD THỰC TẾ NẾU CÓ CHỌN FILE MỚI
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['image_file']['tmp_name'];
            $file_name = time() . '_' . basename($_FILES['image_file']['name']);
            $target_dir = "../images/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $target_dir . $file_name)) {
                $image_db_path = 'images/' . $file_name;
            }
        }

        $sql = "UPDATE products 
                SET name = :name, price = :price, old_price = :old_price, image = :image, tag = :tag, category = :category, stock = :stock 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':price' => $price,
            ':old_price' => $old_price,
            ':image' => $image_db_path,
            ':tag' => $tag,
            ':category' => $category,
            ':stock' => $stock,
            ':id' => $id
        ]);

        echo "<script>alert('Cập nhật thông tin sản phẩm thành công!'); window.location.href='index.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi hệ thống: Không thể lưu thay đổi!'); window.history.back();</script>";
        exit;
    }
}
?>