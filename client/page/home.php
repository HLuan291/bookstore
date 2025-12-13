<?php
// 1. TRUY VẤN SÁCH MỚI (Lấy 8 cuốn mới nhất theo ngày tạo)
$new_books = db_fetch_all("SELECT * FROM sach ORDER BY ngay_tao DESC LIMIT 8");

// 2. TRUY VẤN SÁCH HOT (Lấy ngẫu nhiên 8 cuốn để hiển thị đa dạng)
$hot_books = db_fetch_all("SELECT * FROM sach ORDER BY RAND() LIMIT 8");
?>

<link rel="stylesheet" href="assets/css/home.css">

<section class="hero-banner">
    <div class="hero-content">
        <h1>Thế Giới Sách<br>Trong Tầm Tay</h1>
        <p>Khám phá những tựa sách bán chạy nhất và mới nhất vừa cập bến. Đọc sách là cách tốt nhất để nuôi dưỡng tâm hồn.</p>
        <a class="hero-btn" href="index.php?page=category">Mua Sắm Ngay</a>
    </div>
</section>

<section class="home-section">
    <div class="section-header">
        <h2 class="section-title">Khám Phá Danh Mục</h2>
    </div>
    
    <div class="category-grid">
        <a href="index.php?page=category&id=1" class="cat-item">
            <div class="cat-img">
                <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" alt="Giáo khoa">
            </div>
            <span>Sách Giáo Khoa</span>
        </a>
        
        <a href="index.php?page=category&id=2" class="cat-item">
            <div class="cat-img">
                <img src="https://cdn-icons-png.flaticon.com/512/3389/3389081.png" alt="Văn học">
            </div>
            <span>Văn Học</span>
        </a>

        <a href="index.php?page=category&id=3" class="cat-item">
            <div class="cat-img">
                <img src="https://cdn-icons-png.flaticon.com/512/864/864685.png" alt="Thiếu nhi">
            </div>
            <span>Thiếu Nhi</span>
        </a>

        <a href="index.php?page=category&id=4" class="cat-item">
            <div class="cat-img">
                <img src="https://cdn-icons-png.flaticon.com/512/1903/1903162.png" alt="Kinh tế">
            </div>
            <span>Kinh Tế</span>
        </a>
        
        <a href="index.php?page=category&id=5" class="cat-item">
            <div class="cat-img">
                <img src="https://cdn-icons-png.flaticon.com/512/3079/3079006.png" alt="Ngoại ngữ">
            </div>
            <span>Ngoại Ngữ</span>
        </a>
        
        <a href="index.php?page=category&id=6" class="cat-item">
            <div class="cat-img">
                <img src="https://cdn-icons-png.flaticon.com/512/4322/4322992.png" alt="Tâm lý">
            </div>
            <span>Tâm Lý</span>
        </a>
    </div>
</section>

<section class="home-section">
    <div class="section-header">
        <h2 class="section-title">📚 Sách Mới Cập Nhật</h2>
        <a href="index.php?page=new" class="view-all">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="book-grid">
        <?php foreach ($new_books as $b): ?>
        <div class="book-card">
            <div class="card-img-wrapper">
                <a href="index.php?page=product&id=<?= $b['id_sach'] ?>">
                    <img src="../admin/uploads/<?= $b['hinh_anh'] ?>" alt="<?= $b['ten_sach'] ?>">
                </a>
                <span class="badge-new">Mới</span>
            </div>

            <div class="card-info">
                <div class="card-cate">Sách Mới</div>
                <h3 class="card-title">
                    <a href="index.php?page=product&id=<?= $b['id_sach'] ?>"><?= $b['ten_sach'] ?></a>
                </h3>
                <div class="card-price">
                    <?= number_format($b['gia']) ?>₫
                </div>
                
                <button class="btn-add-cart" onclick="addToCart('<?= $b['id_sach'] ?>')">
                    <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="home-section">
    <div class="promo-banner">
        <div class="promo-content">
            <h3>Đại Tiệc Sách - Giảm Đến 50%</h3>
            <p>Săn ngay deal hot cuối tháng. Miễn phí vận chuyển cho đơn từ 200k.</p>
            <a href="index.php?page=category" class="promo-btn">Xem Ngay</a>
        </div>
        <img src="https://img.freepik.com/free-photo/row-books-shelf_1232-4217.jpg?w=1380" alt="Promo">
    </div>
</section>

<section class="home-section bg-light">
    <div class="section-header">
        <h2 class="section-title">🔥 Sách Đang Hot</h2>
        <a href="index.php?page=highlight" class="view-all">Xem tất cả <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="book-grid">
        <?php foreach ($hot_books as $b): ?>
        <div class="book-card">
            <div class="card-img-wrapper">
                <a href="index.php?page=product&id=<?= $b['id_sach'] ?>">
                    <img src="../admin/uploads/<?= $b['hinh_anh'] ?>" alt="<?= $b['ten_sach'] ?>">
                </a>
                <span class="badge-hot">Hot</span>
            </div>

            <div class="card-info">
                <div class="card-cate">Nổi bật</div>
                <h3 class="card-title">
                    <a href="index.php?page=product&id=<?= $b['id_sach'] ?>"><?= $b['ten_sach'] ?></a>
                </h3>
                <div class="card-price">
                    <?= number_format($b['gia']) ?>₫
                </div>

                <button class="btn-add-cart" onclick="addToCart('<?= $b['id_sach'] ?>')">
                    <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="home-section service-section">
    <div class="service-grid">
        <div class="service-item">
            <i class="fa-solid fa-truck-fast"></i>
            <h4>Giao Hàng Nhanh</h4>
            <p>Vận chuyển toàn quốc 2-4 ngày</p>
        </div>
        <div class="service-item">
            <i class="fa-solid fa-shield-halved"></i>
            <h4>Bảo Mật 100%</h4>
            <p>Thanh toán an toàn tuyệt đối</p>
        </div>
        <div class="service-item">
            <i class="fa-solid fa-rotate-left"></i>
            <h4>Đổi Trả Dễ Dàng</h4>
            <p>Đổi trả miễn phí trong 7 ngày</p>
        </div>
        <div class="service-item">
            <i class="fa-solid fa-headset"></i>
            <h4>Hỗ Trợ 24/7</h4>
            <p>Hotline hỗ trợ khách hàng</p>
        </div>
    </div>
</section>