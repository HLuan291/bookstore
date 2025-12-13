<?php
// 1. Lấy từ khóa tìm kiếm
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$books = [];

// 2. Nếu có từ khóa -> Truy vấn Database
if ($keyword !== '') {
    // Tìm kiếm theo Tên sách HOẶC Tác giả
    // Sử dụng %...% để tìm tương đối (VD: gõ "Harry" ra "Harry Potter")
    $sql = "SELECT * FROM sach 
            WHERE ten_sach LIKE :kw 
            OR tac_gia LIKE :kw 
            ORDER BY ten_sach ASC";
            
    $books = db_fetch_all($sql, [':kw' => "%$keyword%"]);
}
?>

<link rel="stylesheet" href="assets/css/home.css">

<section class="home-section" style="margin-top: 40px;">
    
    <div class="section-header">
        <?php if ($keyword === ''): ?>
            <h2 class="section-title">Vui lòng nhập từ khóa tìm kiếm</h2>
        <?php else: ?>
            <h2 class="section-title">
                Kết quả tìm kiếm cho: "<?= htmlspecialchars($keyword) ?>"
                <span style="font-size: 16px; color: #666; font-weight: 400;">
                    (Tìm thấy <?= count($books) ?> sản phẩm)
                </span>
            </h2>
        <?php endif; ?>
    </div>

    <?php if (empty($books) && $keyword !== ''): ?>
        
        <div style="text-align: center; padding: 50px; background: #fff; border-radius: 12px; border: 1px solid #eee;">
            <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" alt="Not found" style="width: 80px; opacity: 0.5; margin-bottom: 20px;">
            <p style="font-size: 18px; color: #555;">Rất tiếc, không tìm thấy cuốn sách nào phù hợp với từ khóa <strong>"<?= htmlspecialchars($keyword) ?>"</strong>.</p>
            <a href="index.php" style="color: #2563eb; text-decoration: none; margin-top: 10px; display: inline-block;">Quay lại trang chủ</a>
        </div>

    <?php else: ?>
        
        <div class="book-grid">
            <?php foreach ($books as $b): ?>
            <div class="book-card">
                <div class="card-img-wrapper">
                    <a href="index.php?page=product&id=<?= $b['id_sach'] ?>">
                        <img src="../admin/uploads/<?= $b['hinh_anh'] ?>" alt="<?= $b['ten_sach'] ?>">
                    </a>
                </div>

                <div class="card-info">
                    <div class="card-cate">Sách</div>
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

    <?php endif; ?>

</section>