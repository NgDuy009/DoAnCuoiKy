<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

if (isset($conn) && !isset($pdo)) { $pdo = $conn; }
if (isset($pdo) && !isset($conn)) { $conn = $pdo; }

if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status_value'];
    
    $stmt_update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt_update->execute([$new_status, $order_id]);
    
    header("Location: index.php");
    exit;
}

$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_stock_res = $pdo->query("SELECT SUM(stock) FROM products")->fetchColumn();
$total_stock = $total_stock_res ? $total_stock_res : 0;
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

$chart_stmt = $pdo->query("SELECT tag, COUNT(*) as count FROM products WHERE tag IS NOT NULL AND tag != '' GROUP BY tag");
$chart_data = $chart_stmt ? $chart_stmt->fetchAll(PDO::FETCH_ASSOC) : [];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_tag = isset($_GET['filter_tag']) ? trim($_GET['filter_tag']) : '';
$sort_by = isset($_GET['sort_by']) ? trim($_GET['sort_by']) : 'id_desc';

$tags_stmt = $pdo->query("SELECT DISTINCT tag FROM products WHERE tag IS NOT NULL AND tag != ''");
$tags_list = $tags_stmt ? $tags_stmt->fetchAll(PDO::FETCH_COLUMN) : [];

$where_clause = " WHERE 1=1";
$params = [];

if (!empty($search)) {
    $where_clause .= " AND (name LIKE :search_name OR tag LIKE :search_tag)";
    $params[':search_name'] = "%" . $search . "%";
    $params[':search_tag'] = "%" . $search . "%";
}
if (!empty($filter_tag)) {
    $where_clause .= " AND tag = :tag";
    $params[':tag'] = $filter_tag;
}

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$count_sql = "SELECT COUNT(*) FROM products" . $where_clause;
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();

$total_pages = ceil($total_records / $limit);
if ($total_pages < 1) $total_pages = 1;
if ($page > $total_pages) $page = $total_pages;

$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM products" . $where_clause;

switch ($sort_by) {
    case 'price_asc': $sql .= " ORDER BY price ASC"; break;
    case 'price_desc': $sql .= " ORDER BY price DESC"; break;
    case 'stock_desc': $sql .= " ORDER BY stock DESC"; break;
    default: $sql .= " ORDER BY id DESC"; break;
}

$sql .= " LIMIT $limit OFFSET $offset";

$stmt_main = $pdo->prepare($sql);
$stmt_main->execute($params);
$products = $stmt_main->fetchAll(PDO::FETCH_ASSOC);

$orders_stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
$orders = $orders_stmt ? $orders_stmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ Thống Quản Trị TZY Store</title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-sidebar a:hover { background: #343a40; border-radius: 4px; }
        .card { border-radius: 10px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 admin-sidebar" style="background: #212529; min-height: 100vh; padding: 20px; color: white;">
            <h4 class="fw-bold text-white mb-3">TZY Admin</h4>
            <p class="small text-muted mb-3">Hi, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></p>
            <hr class="text-secondary">
            <a href="#dashboard-section" class="text-white d-block p-2 mb-2 text-decoration-none">📊 Dashboard Thống kê</a>
            <a href="#product-section" class="text-white d-block p-2 mb-2 text-decoration-none">📦 Quản lý Sản phẩm</a>
            <a href="#orders-section" class="text-white d-block p-2 mb-2 text-decoration-none">🧾 Quản lý Đơn hàng</a>
            <a href="logout.php" class="text-danger mt-5 d-block p-2 fw-bold text-decoration-none">👋 Đăng xuất</a>
        </div>

        <div class="col-md-10 p-4">
            
            <div id="dashboard-section" class="row mb-4">
                <div class="col-md-8">
                    <h5 class="fw-bold text-uppercase mb-3">Bảng Thống Kê Nhanh</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card p-3 border-0 bg-primary text-white shadow-sm">
                                <h6 class="small text-uppercase text-white-50">Tổng số mặt hàng</h6>
                                <h3><?php echo $total_products; ?> sản phẩm</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 border-0 bg-success text-white shadow-sm">
                                <h6 class="small text-uppercase text-white-50">Số lượng trong kho</h6>
                                <h3><?php echo number_format($total_stock); ?> cái</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 border-0 bg-dark text-white shadow-sm">
                                <h6 class="small text-uppercase text-white-50">Đơn đặt hàng</h6>
                                <h3><?php echo $total_orders; ?> đơn</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-body shadow-sm border-0 align-items-center justify-content-center" style="height: 100%;">
                        <h6 class="fw-bold text-uppercase mb-2 text-center" style="font-size:12px;">Tỷ Lệ Hàng Hóa Theo Hãng</h6>
                        <div style="width: 130px; height: 130px;">
                            <canvas id="brandChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div id="product-section" class="card card-body shadow-sm border-0 mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <h5 class="fw-bold text-uppercase m-0 text-dark">Danh Mục Quản Lý Sản Phẩm</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <button onclick="exportToExcel()" class="btn btn-sm btn-outline-success fw-bold">📥 Xuất File Excel</button>
                        <a href="create.php" class="btn btn-sm btn-primary fw-bold">+ Thêm Mới Dữ Liệu</a>
                    </div>
                </div>

                <form action="index.php" method="GET" class="row g-2 mb-4 bg-light p-3 rounded border">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Từ khóa tìm kiếm</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm tên hoặc hãng..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Lọc theo hãng</label>
                        <select name="filter_tag" class="form-select form-select-sm">
                            <option value="">-- Tất cả các hãng --</option>
                            <?php foreach($tags_list as $t): ?>
                                <option value="<?php echo htmlspecialchars($t); ?>" <?php if($filter_tag == $t) echo 'selected'; ?>><?php echo htmlspecialchars($t); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Sắp xếp dữ liệu</label>
                        <select name="sort_by" class="form-select form-select-sm">
                            <option value="id_desc" <?php if($sort_by == 'id_desc') echo 'selected'; ?>>Mới nhất lên đầu</option>
                            <option value="price_asc" <?php if($sort_by == 'price_asc') echo 'selected'; ?>>Giá tăng dần</option>
                            <option value="price_desc" <?php if($sort_by == 'price_desc') echo 'selected'; ?>>Giá giảm dần</option>
                            <option value="stock_desc" <?php if($sort_by == 'stock_desc') echo 'selected'; ?>>Kho số lượng lớn</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-sm btn-dark w-100">Áp Dụng</button>
                        <a href="index.php" class="btn btn-sm btn-outline-secondary">Xóa</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="excel-export-source" class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="8%">Hình Ảnh</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Thương Hiệu</th>
                                <th>Giá Bán</th>
                                <th width="10%">Kho</th>
                                <th width="12%" class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($products)): foreach ($products as $p): ?>
                            <tr>
                                <td><?php echo $p['id']; ?></td>
                                <td class="text-center">
                                    <img src="../<?php echo htmlspecialchars($p['image']); ?>" onerror="this.src='../images/default.jpg'" style="width:50px; height:60px; object-fit:cover;" alt="Ảnh">
                                </td>
                                <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($p['tag']); ?></span></td>
                                <td class="text-danger fw-bold"><?php echo number_format((float)$p['price'], 0, ',', '.'); ?> đ</td>
                                <td><?php echo $p['stock']; ?> cái</td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                                    <a href="delete.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Ní chắc chắn muốn xóa sản phẩm này không?')" class="btn btn-sm btn-danger fw-bold">Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">Không tìm thấy bản ghi sản phẩm nào trùng khớp!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($total_pages > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination pagination-sm justify-content-center">
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if($page == $i) echo 'active'; ?>">
                                <a class="page-link" href="index.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter_tag=<?php echo urlencode($filter_tag); ?>&sort_by=<?php echo $sort_by; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>

            <div id="orders-section" class="card card-body shadow-sm border-0 mb-4 mt-4">
                <h5 class="fw-bold text-uppercase mb-4 text-dark">📦 Danh Sách Quản Lý Đơn Hàng</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="8%">Mã Đơn</th>
                                <th width="20%">Thông Tin Khách Hàng</th>
                                <th>Địa Chỉ Giao Hàng</th>
                                <th width="15%">Tổng Thanh Toán</th>
                                <th width="15%">Trạng Thái</th>
                                <th width="15%" class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($orders)): foreach($orders as $order): ?>
                            <tr>
                                <td><strong>#TZY<?php echo $order['id']; ?></strong></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($order['fullname']); ?></div>
                                    <div class="small text-muted">📞 <?php echo htmlspecialchars($order['phone']); ?></div>
                                    <div class="small text-muted">✉️ <?php echo htmlspecialchars($order['user_email']); ?></div>
                                </td>
                                <td><span class="small"><?php echo htmlspecialchars($order['address']); ?></span></td>
                                <td><strong class="text-danger"><?php echo number_format((float)$order['total_amount'], 0, ',', '.'); ?> đ</strong></td>
                                <td>
                                    <?php 
                                        if ($order['status'] == 'Đang xử lý') {
                                            echo '<span class="badge bg-warning text-dark">⏳ Đang xử lý</span>';
                                        } elseif ($order['status'] == 'Đã hoàn thành') {
                                            echo '<span class="badge bg-success">✅ Đã hoàn thành</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">❌ Đã hủy</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <form action="index.php" method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <input type="hidden" name="update_status" value="1">
                                        <select name="status_value" class="form-select form-select-sm" style="font-size: 12px;">
                                            <option value="Đang xử lý" <?php if($order['status'] == 'Đang xử lý') echo 'selected'; ?>>Chờ xử lý</option>
                                            <option value="Đã hoàn thành" <?php if($order['status'] == 'Đã hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                                            <option value="Đã hủy" <?php if($order['status'] == 'Đã hủy') echo 'selected'; ?>>Hủy đơn</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-dark fw-bold" style="font-size: 11px;">Lưu</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted py-3">Hiện tại hệ thống chưa nhận được đơn đặt hàng nào!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('brandChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_column($chart_data, 'tag') ?: []); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($chart_data, 'count') ?: []); ?>,
                        backgroundColor: ['#212529', '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }
    });

    function exportToExcel() {
        var tab_text = "<table border='1'><tr bgcolor='#87AFC7'>";
        var tab = document.getElementById('excel-export-source');
        for (var j = 0 ; j < tab.rows.length ; j++) {     
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        }
        tab_text = tab_text + "</table>";
        var ctx = { worksheet: 'DanhSachSanPham', table: tab_text };
        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="utf-8"></head><body>{table}</body></html>';
        function format(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
        var blob = new Blob([format(template, ctx)], { type: "application/vnd.ms-excel;charset=utf-8;" });
        var downloadLink = document.createElement("a");
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = "danh-sach-san-pham.xls";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
</body>
</html>