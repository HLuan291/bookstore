<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKSTORE</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- ĐÚNG NHẤT: -->
    <link rel="stylesheet" href="assets/css/header.css">
</head>

<body>
<header class="header">

    <div class="header-container">

        <a href="index.php" class="logo">
            <i class="fa-solid fa-book-open"></i> <span>BOOKSTORE</span>
        </a>

        <nav class="menu">
            <a href="index.php?page=highlight">Sách Nổi Bật</a>
            <a href="index.php?page=new">Sách Mới Cập Nhật</a>
            <a href="index.php?page=category">Thể Loại</a>
        </nav>

        <div class="right-box">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Tìm kiếm">
            </div>

            <a href="index.php?page=cart" class="icon-btn">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?page=profile" class="icon-btn" title="Hồ sơ">
                    <i class="fa-regular fa-user"></i>
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
