<?php
session_start();
if (file_exists('config/database.php')) { require_once 'config/database.php'; }
if (isset($conn) && !isset($pdo)) { $pdo = $conn; }

$tag_filter = isset($_GET['tag']) ? trim($_GET['tag']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$products = []; $all_tags = [];

if (isset($pdo)) {
    try {
        $all_tags = $pdo->query("SELECT DISTINCT tag FROM products WHERE tag IS NOT NULL AND tag != ''")->fetchAll(PDO::FETCH_COLUMN);
        
        $sql = "SELECT * FROM products WHERE 1=1"; 
        $params = [];
        if (!empty($tag_filter)) { 
            $sql .= " AND tag = :tag"; 
            $params[':tag'] = $tag_filter; 
        }
        if (!empty($search)) { 
            $sql .= " AND (name LIKE :search_name OR tag LIKE :search_tag)"; 
            $params[':search_name'] = '%' . $search . '%';
            $params[':search_tag'] = '%' . $search . '%';
        }
        
        $stmt = $pdo->prepare($sql . " ORDER BY id DESC"); 
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TZY STORE | Premium Aesthetics</title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #fbfbfc; color: #1a1a1a; font-family: 'Plus Jakarta Sans', sans-serif; opacity: 0; transform: translateY(10px); animation: fadeIn 0.6s ease forwards; }
        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }
        .tzy-navbar { background: rgba(251, 251, 252, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-bottom: 1px solid rgba(0,0,0,0.05); }
        .nav-link { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #7a7a7a !important; transition: 0.3s; }
        .nav-link.active, .nav-link:hover { color: #1a1a1a !important; }
        .search-box { background: #fff; border: 1px solid rgba(0,0,0,0.05); border-radius: 30px; padding: 6px 6px 6px 20px; max-width: 500px; margin: 0 auto; }
        .banner-wrap { width: 100%; padding-top: 35%; position: relative; border-radius: 16px; overflow: hidden; background: #eee; }
        .banner-wrap img { position: absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; transition: 1s; }
        .p-card { background: #fff; border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: 0.3s; }
        .p-card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.02); }
        .img-wrap { position: relative; padding-top: 125%; overflow: hidden; background: #f8f8fa; }
        .img-wrap img { position: absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; transition: 0.5s; }
        .p-card:hover .img-wrap img { transform: scale(1.05); }
    </style>
</head>
<body style="padding-top: 100px;">

    <nav class="navbar navbar-expand-lg tzy-navbar fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php" style="letter-spacing:0.2em; font-size:16px;">TZY STORE</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#n"><i class="bi bi-list fs-4"></i></button>
            <div class="collapse navbar-collapse" id="n">
                <ul class="navbar-nav mx-auto gap-2">
                    <li class="nav-item"><a class="nav-link <?php echo empty($tag_filter)?'active':''; ?>" href="index.php">Tất cả</a></li>
                    <?php foreach ($all_tags as $t): ?>
                        <li class="nav-item"><a class="nav-link <?php echo $tag_filter===$t?'active':''; ?>" href="index.php?tag=<?php echo urlencode($t); ?>"><?php echo htmlspecialchars($t); ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <a href="cart.php" class="text-dark position-relative fs-5">
                    <i class="bi bi-bag"></i>
                    <?php if(!empty($_SESSION['cart'])): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="font-size:9px;"><?php echo array_sum($_SESSION['cart']); ?></span><?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <form action="index.php" method="GET" class="search-box d-flex align-items-center mb-5 shadow-sm">
            <input type="text" name="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search); ?>" style="font-size:13px;">
            <button type="submit" class="btn btn-dark rounded-pill px-4 py-2 text-uppercase" style="font-size:11px; font-weight:600;"><i class="bi bi-search me-1"></i>Tìm</button>
        </form>

        <div class="banner-wrap shadow-sm mb-5">
            <img src="https://cdn.phototourl.com/free/2026-06-28-1d8a866d-3e6f-41d4-9326-13d835265c56.jpg" alt="Banner">
        </div>

        <?php if (empty($products)): ?>
            <p class="text-center text-muted my-5">Không tìm thấy sản phẩm nào phù hợp.</p>
        <?php else: ?>
            <div class="row g-3 g-md-4">
                <?php foreach ($products as $p): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="p-card">
                            <div class="img-wrap">
                                <a href="detail.php?id=<?php echo $p['id']; ?>"><img src="<?php echo htmlspecialchars($p['image']); ?>" onerror="this.src='images/default.jpg'"></a>
                            </div>
                            <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <span class="text-muted text-uppercase d-block mb-1" style="font-size:10px; letter-spacing:0.05em;"><?php echo htmlspecialchars($p['tag']); ?></span>
                                    <a href="detail.php?id=<?php echo $p['id']; ?>" class="text-decoration-none text-dark fw-medium d-block mb-2 text-truncate" style="font-size:14px;"><?php echo htmlspecialchars($p['name']); ?></a>
                                </div>
                                <div>
                                    <div class="fw-bold mb-3" style="font-size:14px;"><?php echo number_format((float)$p['price'], 0, ',', '.'); ?> đ</div>
                                    <a href="detail.php?id=<?php echo $p['id']; ?>" class="btn btn-light w-100 py-2 border text-uppercase" style="font-size:11px; font-weight:600;">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-white border-top py-5 mt-5" style="font-size:13px; color:#7a7a7a;">
        <div class="container">
            <div class="row g-4 text-start mb-4">
                <div class="col-md-4">
                    <div class="fw-bold text-dark mb-2">TZY STORE</div>
                    <p class="small">Thương hiệu thời trang tối giản nâng tầm phong cách sống.</p>
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <div class="fw-bold text-dark mb-2">BẢN TIN</div>
                    <p class="small">Nhận ưu đãi độc quyền nhanh nhất.</p>
                </div>
            </div>
            <p class="small text-center mb-0 pt-3 border-top">© 2026 TZY STORE. Powered by Minimalist Luxury Aesthetics.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>