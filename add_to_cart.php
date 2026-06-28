<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$qty = isset($_REQUEST['qty']) ? intval($_REQUEST['qty']) : 1;

if ($qty < 1) { $qty = 1; }

if ($id > 0) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
    header("Location: cart.php");
    exit;
}

header("Location: index.php");
exit;
?>