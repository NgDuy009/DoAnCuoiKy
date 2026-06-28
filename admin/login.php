<?php
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
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     $conn = $pdo; 
} catch (\PDOException $e) {
     die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>

<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && (password_verify($password, $admin['password']) || $password === $admin['password'])) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_name'] = $admin['fullname'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Tài khoản hoặc mật khẩu không chính xác!';
        }
    } else {
        $error = 'Vui lòng nhập đầy đủ thông tin!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Hệ Thống | TZY </title>
    <link rel="icon" type="image/png" href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-login: #f8f9fa;
            --card-bg: #ffffff;
            --text-main: #212529;
            --border-color: #dee2e6;
        }
        body.dark-mode {
            --bg-login: #121212;
            --card-bg: #1e1e1e;
            --text-main: #e0e0e0;
            --border-color: #333333;
        }
        
        body {
            background-color: var(--bg-login);
            color: var(--text-main);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .split-container {
            height: 100vh;
            display: flex;
        }
        .split-banner {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), 
                        url('https://cdn.phototourl.com/free/2026-06-28-72bbea41-98c5-481d-bf7d-60d4e8598133.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: #ffffff;
        }
        .split-form-zone {
            width: 450px;
            background-color: var(--card-bg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px;
            border-left: 1px solid var(--border-color);
            position: relative;
        }
        .form-control-custom {
            border: none;
            border-bottom: 2px solid var(--border-color);
            border-radius: 0;
            padding: 10px 5px;
            background-color: transparent !important;
            color: var(--text-main) !important;
            box-shadow: none !important;
            transition: border-color 0.3s;
        }
        .form-control-custom:focus {
            border-bottom-color: #000000;
        }
        body.dark-mode .form-control-custom:focus {
            border-bottom-color: #ffffff;
        }
        .btn-luxury {
            background-color: #212529;
            color: #ffffff;
            border: none;
            border-radius: 0;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-luxury:hover {
            background-color: #000000;
            transform: translateY(-2px);
        }
        body.dark-mode .btn-luxury {
            background-color: #ffffff;
            color: #121212;
        }
        body.dark-mode .btn-luxury:hover {
            background-color: #e0e0e0;
        }
        
        @media (max-width: 768px) {
            .split-banner { display: none; }
            .split-form-zone { width: 100%; padding: 30px; }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="split-container">
        
        <div class="split-banner">
            <h1 class="fw-bold tracking-widest text-uppercase" style="font-size: 3.5rem; letter-spacing: 4px;">TZY STORE</h1>
            <p class="lead text-white-50">THE NEW ERA OF LUXURY COMFORT</p>
            <div class="mt-5 pt-5 border-top border-secondary" style="max-width: 400px;">
                <small class="text-white-50 d-block mb-2">Hệ Thống Phân Phối Toàn Cầu</small>
                <span class="badge bg-outline-light border px-3 py-2 text-uppercase small">Bản phát hành TZY</span>
            </div>
        </div>

        <div class="split-form-zone">
            
            <div class="mb-5 text-center text-md-start">
                <h3 class="fw-bold text-uppercase m-0" style="letter-spacing: 1px;">Đăng Nhập Quản Trị</h3>
                <p class="text-muted small">Vui lòng điền thông tin để truy cập hệ thống.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger p-2 small border-0 rounded-0" id="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>

            <form id="adminLoginForm" action="login.php" method="POST">
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase text-muted" style="font-size: 11px;">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" class="form-control form-control-custom" autocomplete="off" placeholder="Nhập tài khoản admin...">
                </div>
                
                <div class="mb-5">
                    <label class="form-label small fw-bold text-uppercase text-muted" style="font-size: 11px;">Mật khẩu hệ thống</label>
                    <input type="password" id="password" name="password" class="form-control form-control-custom" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-luxury w-100 text-uppercase shadow-sm">Xác Minh Truy Cập &rarr;</button>
            </form>

            <div class="position-absolute bottom-0 start-0 w-100 p-4 text-center">
                <a href="../index.php" class="text-decoration-none text-muted small">&larr; Trở về trang chủ cửa hàng</a>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('admin_dark_mode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }

        const loginForm = document.getElementById('adminLoginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const user = document.getElementById('username').value.trim();
                const pass = document.getElementById('password').value.trim();

                if (user === '' || pass === '') {
                    e.preventDefault(); // Chặn form gửi lên server
                    alert(', vui lòng không được bỏ trống Tên đăng nhập hoặc Mật khẩu nha!');
                }
            });
        }
    });
</script>

</body>
</html>