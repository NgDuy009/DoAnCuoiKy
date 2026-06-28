<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$nav_tags = [];
if (isset($pdo)) {
    try {
        $tag_stmt = $pdo->query("SELECT DISTINCT tag FROM products WHERE tag IS NOT NULL AND tag != ''");
        $nav_tags = $tag_stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch(Exception $e) {}
}
$current_tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';
$current_search = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<header class="modern-header">
    <div class="container">
        
        <div class="tier-main">
            <div class="tier-left d-none d-md-block">
                <small class="text-uppercase text-muted" style="font-size:10px; letter-spacing:0.2em;">TZY Studio @2026</small>
            </div>
            
            <div class="tier-center">
                <a class="main-logo-anchor" href="index.php">TZY PARIS</a>
            </div>
            
            <div class="tier-right">
                <form action="index.php" method="GET" class="lux-search-form m-0" onsubmit="return verifySearchForm(this)">
                    <?php if(!empty($current_tag)): ?>
                        <input type="hidden" name="tag" value="<?php echo htmlspecialchars($current_tag); ?>">
                    <?php endif; ?>
                    <input type="text" name="search" placeholder="Tìm kiếm trang phục..." value="<?php echo htmlspecialchars($current_search); ?>">
                    <button type="submit"><i class="bi bi-search"></i></button>
                </form>
                
                <a href="cart.php" class="text-dark position-relative p-1">
                    <i class="bi bi-handbag fs-5"></i>
                    <?php if(!empty($_SESSION['cart'])): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-dark text-white" style="font-size:8px; padding:3px 5px;">
                            <?php echo array_sum($_SESSION['cart']); ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <div class="tier-categories">
            <ul class="luxury-tags-nav">
                <li>
                    <a class="luxury-tag-link <?php echo empty($current_tag)?'active':''; ?>" href="index.php">Tất Cả Trang Phục</a>
                </li>
                <?php foreach ($nav_tags as $tag_item): ?>
                    <li>
                        <a class="luxury-tag-link <?php echo $current_tag === $tag_item ? 'active' : ''; ?>" href="index.php?tag=<?php echo urlencode($tag_item); ?>">
                            <?php echo htmlspecialchars($tag_item); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</header>