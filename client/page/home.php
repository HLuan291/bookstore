<?php
$books = db_fetch_all("SELECT * FROM sach ORDER BY ngay_tao DESC LIMIT 12");
?>

<link rel="stylesheet" href="/client/assets/css/home.css">

<!-- HERO BANNER -->
<section class="hero-banner">
    <div class="hero-content">
        <h1>Khám Phá Cuốn Sách<br>Hay Tiếp Theo</h1>
        <p>Những cuốn sách mới nhất và hấp dẫn đang chờ bạn. Đắm mình vào những thế giới mới ngay hôm nay.</p>
        <a class="hero-btn" href="index.php?page=category">Khám Phá Ngay</a>
    </div>
</section>

<!-- BOOK HOT -->
<section class="home-section">

    <div class="section-header">
        <h2>Sách Đang Hot</h2>
        <a href="index.php?page=category" class="view-all">Xem tất cả</a>
    </div>

    <div class="book-slider">

        <?php foreach ($books as $b): ?>
        <div class="book-card">

            <div class="book-image">
                <img src="../admin/uploads/<?= $b['hinh_anh'] ?>" alt="<?= $b['ten_sach'] ?>">
            </div>

            <div class="book-info">
                <h3 class="book-title"><?= $b['ten_sach'] ?></h3>
                <p class="book-author"><?= $b['tac_gia'] ?></p>
                <p class="book-price"><?= number_format($b['gia']) ?>đ</p>

                <a class="book-btn" href="index.php?page=product&id=<?= $b['id_sach'] ?>">Thêm vào giỏ</a>
            </div>

        </div>
        <?php endforeach; ?>

    </div>
</section>
<!-- BOOK moi cap nhat -->
<section class="home-section">

    <div class="section-header">
        <h2>Sách Mới Cập Nhật</h2>
        <a href="index.php?page=category" class="view-all">Xem tất cả</a>
    </div>

    <div class="book-slider">

        <?php foreach ($books as $b): ?>
        <div class="book-card">

            <div class="book-image">
                <img src="../admin/uploads/<?= $b['hinh_anh'] ?>" alt="<?= $b['ten_sach'] ?>">
            </div>

            <div class="book-info">
                <h3 class="book-title"><?= $b['ten_sach'] ?></h3>
                <p class="book-author"><?= $b['tac_gia'] ?></p>
                <p class="book-price"><?= number_format($b['gia']) ?>đ</p>

                <a class="book-btn" href="index.php?page=product&id=<?= $b['id_sach'] ?>">Thêm vào giỏ</a>
            </div>

        </div>
        <?php endforeach; ?>

    </div>
</section>