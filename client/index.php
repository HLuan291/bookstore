<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

$page = $_GET['page'] ?? 'home';

// --- Các trang KHÔNG dùng header/footer ---
$noHeaderPages = ['dangnhap', 'dangky'];

if (!in_array($page, $noHeaderPages)) {
    include __DIR__ . '/includes/header.php';
}

// ---------------- ROUTER ----------------
switch ($page) {

    case 'dangnhap':
        include __DIR__ . '/page/dangnhap.php';
        break;

    case 'dangky':
        include __DIR__ . '/page/dangky.php';
        break;

    case 'category':
        include __DIR__ . '/page/category.php';
        break;

    case 'product':
        include __DIR__ . '/page/product_detail.php';
        break;

    case 'cart':
        include __DIR__ . '/page/cart.php';
        break;

    case 'checkout':
        include __DIR__ . '/page/checkout.php';
        break;

    case 'profile':
        include __DIR__ . '/page/profile.php';
        break;

    case 'orders':
        include __DIR__ . '/page/orders.php';
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php");
        exit;

    default:
        include __DIR__ . '/page/home.php';
        break;
}

// --- Footer chỉ load khi không phải login/register ---
if (!in_array($page, $noHeaderPages)) {
    include __DIR__ . '/includes/footer.php';
}
