<?php
// Đếm số lượng sản phẩm trong Session
$total_qty_header = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $total_qty_header += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKSTORE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/header.css">
    <style>
        /* CSS cho số lượng trên icon */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ff3b30;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 0;
            width: 18px;
            height: 18px;
            display: flex; /* Flex giúp căn giữa số 1 cách hoàn hảo */
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid #fff;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="header-container">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-book-open"></i> <span>BOOKSTORE</span>
        </a>
        <nav class="menu">
            <a href="index.php?page=highlight">Nổi Bật</a>
            <a href="index.php?page=new">Mới Cập Nhật</a>
            <a href="index.php?page=category">Thể Loại</a>
        </nav>
        <div class="right-box">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Tìm kiếm">
            </div>

            <a href="index.php?page=cart" class="icon-btn" style="position: relative;">
                <i class="fa-solid fa-cart-shopping"></i>
                
                <span id="header-cart-count" class="cart-badge" 
                      style="<?php echo ($total_qty_header > 0) ? 'display:flex;' : 'display:none;'; ?>">
                    <?= $total_qty_header ?>
                </span>
            </a>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?page=profile" class="icon-btn"><i class="fa-regular fa-user"></i></a>
                <a href="index.php?page=logout" class="icon-btn"><i class="fa-solid fa-sign-out-alt"></i></a>
            <?php else: ?>
                <a href="index.php?page=dangnhap" class="icon-btn"><i class="fa-regular fa-user"></i></a>
            <?php endif; ?>
        </div>
    </div>
</header>
<div style="height:80px;"></div>