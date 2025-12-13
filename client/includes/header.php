<?php
// 1. ĐẾM SỐ LƯỢNG GIỎ HÀNG
$total_qty_header = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $total_qty_header += $qty;
    }
}

// 2. LẤY AVATAR NGƯỜI DÙNG (NẾU ĐÃ ĐĂNG NHẬP)
$header_avatar_html = '<i class="fa-regular fa-user"></i>'; // Mặc định là icon

if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
    $uid = $_SESSION['user']['id'];
    
    // Gọi hàm db_fetch để lấy thông tin mới nhất từ DB
    // (Giả sử hàm db_fetch đã có trong functions.php được include trước đó)
    if (function_exists('db_fetch')) {
        $u_header = db_fetch("SELECT avatar FROM nguoi_dung WHERE id_nguoidung = :id", [':id' => $uid]);
        
        // Đường dẫn ảnh
        $avt_path = "assets/img/default-avatar.jpg"; // Ảnh mặc định
        
        if ($u_header && !empty($u_header['avatar'])) {
            $user_avt_path = "assets/uploads/avatars/" . $u_header['avatar'];
            if (file_exists($user_avt_path)) {
                $avt_path = $user_avt_path;
            }
        }
        
        // Tạo thẻ IMG thay thế cho Icon
        $header_avatar_html = "<img src='$avt_path' alt='User' class='header-user-avatar'>";
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
            <form action="index.php" method="GET" class="search-box">
    <input type="hidden" name="page" value="search">
    <button type="submit" style="border:none; background:none; cursor:pointer;">
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>
    <input type="text" name="keyword" placeholder="Tìm kiếm sách, tác giả..." 
           value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
</form>

            <a href="index.php?page=cart" class="icon-btn" style="position: relative;">
                <i class="fa-solid fa-cart-shopping"></i>
                <span id="header-cart-count" class="cart-badge" 
                      style="<?php echo ($total_qty_header > 0) ? 'display:flex;' : 'display:none;'; ?>">
                    <?= $total_qty_header ?>
                </span>
            </a>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?page=profile" class="icon-btn" title="Trang cá nhân" style="margin-left: 5px;">
                    <?= $header_avatar_html ?>
                </a>
                <a href="index.php?page=logout" class="icon-btn" title="Đăng xuất">
                    <i class="fa-solid fa-sign-out-alt"></i>
                </a>
            <?php else: ?>
                <a href="index.php?page=dangnhap" class="icon-btn" title="Đăng nhập">
                    <i class="fa-regular fa-user"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
<div style="height:80px;"></div>