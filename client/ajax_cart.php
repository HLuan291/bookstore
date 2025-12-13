<?php
// ajax_cart.php

// 1. Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Lấy ID từ yêu cầu gửi lên
$id = isset($_POST['id']) ? trim($_POST['id']) : '';

if ($id !== '') {
    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Thêm hoặc tăng số lượng
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    // 3. Tính tổng số lượng mới để trả về cho Client
    $total_qty = 0;
    foreach ($_SESSION['cart'] as $qty) {
        $total_qty += $qty;
    }

    // Trả về kết quả dạng JSON
    echo json_encode([
        'status' => 'success',
        'total' => $total_qty,
        'message' => 'Đã thêm vào giỏ hàng!'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi ID sản phẩm']);
}
?>