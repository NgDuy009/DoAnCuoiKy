<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TZY CLOTHING STORE | Premium Atelier</title>

    <link rel="icon" type="image/png"
        href="https://cdn.phototourl.com/free/2026-06-26-7f6da199-bbf8-490d-a72d-5098f107d5d8.jpg">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="public/style.css">

    <!-- =========================
         GLOBAL STYLE FIX + ADMIN UI
    ========================== -->
    <style>

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f6f7fb;
            transition: 0.3s;
        }

        /* DARK MODE */
        body.dark {
            background: #111;
            color: #fff;
        }

        /* NAVBAR */
        .navbar-tzy {
            background: #fff;
            padding: 12px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }

        body.dark .navbar-tzy {
            background: #1a1a1a;
        }

        /* BRAND */
        .brand-text-luxury {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 22px;
        }

        .brand-text-luxury span {
            display: block;
            font-size: 10px;
            letter-spacing: 3px;
            opacity: 0.6;
        }

        /* NAV LINKS */
        .nav-link-tzy {
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #333;
            margin: 0 10px;
            text-decoration: none;
        }

        body.dark .nav-link-tzy {
            color: #ddd;
        }

        .nav-link-tzy:hover {
            color: #000;
        }

        /* SEARCH */
        .search-box {
            width: 180px;
            font-size: 12px;
            border-radius: 20px;
        }

        /* CART */
        .cart-icon-wrapper {
            position: relative;
            margin-left: 15px;
            cursor: pointer;
        }

        .cart-badge-count {
            position: absolute;
            top: -6px;
            right: -8px;
            background: red;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 50%;
        }

        /* ADMIN BUTTON */
        .icon-admin-btn {
            margin-left: 12px;
            color: #333;
            transition: 0.2s;
        }

        .icon-admin-btn:hover {
            color: #000;
            transform: scale(1.1);
        }

        body.dark .icon-admin-btn {
            color: #fff;
        }

        /* DARK BUTTON */
        .dark-toggle {
            border: none;
            background: transparent;
            font-size: 13px;
            margin-left: 15px;
            cursor: pointer;
        }

    </style>

</head>

<body>

<!-- DARK MODE TOGGLE -->
<button class="dark-toggle" onclick="document.body.classList.toggle('dark')">
    🌙 Dark Mode
</button>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-tzy fixed-top">

    <div class="container">

        <!-- BRAND -->
        <a class="navbar-brand-wrapper text-decoration-none" href="index.php">
            <div class="brand-text-luxury">
                TZY
                <span>CLOTHING STORE</span>
            </div>
        </a>

        <!-- MENU -->
        <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex">
            <ul class="navbar-nav">

                <li><a class="nav-link-tzy active" href="index.php">LATEST</a></li>
                <li><a class="nav-link-tzy" href="#">OUTERWEAR</a></li>
                <li><a class="nav-link-tzy" href="#">TOPS</a></li>
                <li><a class="nav-link-tzy" href="#">ACCESSORIES</a></li>

            </ul>
        </div>

        <!-- RIGHT ACTIONS -->
        <div class="d-flex align-items-center">

            <!-- SEARCH -->
            <input type="text"
                class="form-control search-box d-none d-md-block"
                placeholder="Tìm kiếm...">

            <!-- CART -->
            <div class="cart-icon-wrapper">
                <i class="fa-solid fa-bag-shopping fs-5"></i>
                <span class="cart-badge-count">2</span>
            </div>

            <!-- ADMIN ADD -->
            <a href="create.php" class="icon-admin-btn" title="Thêm sản phẩm">
                <i class="fa-solid fa-square-plus fs-5"></i>
            </a>

        </div>

    </div>
</nav>