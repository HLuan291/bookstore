<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

// Lấy tham số 'page' từ URL, mặc định là 'home'
$page = $_GET['page'] ?? 'home';

// --- LUÔN TẢI HEADER ---
// Bỏ qua điều kiện kiểm tra $noHeaderPages
include __DIR__ . '/includes/header.php';

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
        
    case 'search':
        include __DIR__ . '/page/search.php';
        break;

    case 'profile':
        include __DIR__ . '/page/profile.php';
        break;

    case 'orders':
        include __DIR__ . '/page/orders.php';
        break;
    case 'sale':
        include __DIR__ . '/page/category.php'; // Dùng chung giao diện với category
        break;
    case 'new':
        include __DIR__ . '/page/category.php'; // Dùng chung giao diện với category
        break;
    case 'highlight': // Case cho sách hot (khớp với link trong home.php)
        include __DIR__ . '/page/category.php';
        break;
    case 'feedback':
       include __DIR__ . '/page/feedback.php';
       break;
    case 'logout':
        session_destroy();
        header("Location: index.php");
        exit;

    default:
        include __DIR__ . '/page/home.php';
        break;
}

// --- LUÔN TẢI FOOTER ---
// Bỏ qua điều kiện kiểm tra $noHeaderPages
include __DIR__ . '/includes/footer.php';