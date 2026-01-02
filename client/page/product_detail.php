<?php
// Lấy ID từ URL (vì id_sach là char(10) nên lấy dạng chuỗi)
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

// 1. Truy vấn thông tin sách chi tiết
// Sử dụng id_sach thay vì id
$sql = "SELECT * FROM sach WHERE id_sach = :id";
$book = db_fetch($sql, [':id' => $id]);

// Nếu không tìm thấy sách
if (!$book) {
    echo "<div style='text-align:center; padding:80px;'>
            <h2>Sản phẩm không tồn tại!</h2>
            <p>Có thể sản phẩm đã bị xóa hoặc đường dẫn không đúng.</p>
            <a href='index.php' style='color:blue; text-decoration:underline;'>Quay về trang chủ</a>
          </div>";
    return; // Dừng chạy tiếp
}

// 2. Truy vấn sách liên quan (Cùng danh mục nhưng khác ID hiện tại)
$sql_related = "SELECT * FROM sach WHERE id_danhmucc = :iddm AND id_sach != :id ORDER BY rand() LIMIT 4";
// Lưu ý: Tôi dùng tên cột id_danhmuc dựa trên ảnh DB bạn gửi, nếu code báo lỗi hãy kiểm tra lại tên cột này
// Trong ảnh DB của bạn tên cột là `id_danhmuc`, nhưng nếu code cũ dùng `id_danhmucc` (2 chữ c) thì bạn sửa lại nhé.
// Ở đây tôi dùng `id_danhmuc` chuẩn theo ảnh DB.
$sql_related = "SELECT * FROM sach WHERE id_danhmuc = :iddm AND id_sach != :id ORDER BY rand() LIMIT 4";

$related_books = db_fetch_all($sql_related, [
    ':iddm' => $book['id_danhmuc'], 
    ':id'   => $id
]);
?>

<link rel="stylesheet" href="assets/css/product_detail.css">

<div class="pd-container">
    
    <div class="pd-wrapper">
        <div class="pd-image">
            <img src="../admin/uploads/<?= $book['hinh_anh'] ?>" alt="<?= $book['ten_sach'] ?>">
        </div>

        <div class="pd-info">
            <h1 class="pd-title"><?= $book['ten_sach'] ?></h1>
            
            <div class="pd-meta">
                <span>Tác giả: <strong><?= $book['tac_gia'] ?></strong></span>
                <span class="divider">|</span>
                <span>Tình trạng: 
                    <?php if($book['ton_kho'] > 0): ?>
                        <span style="color: #27ae60; font-weight:600;">Còn hàng (<?= $book['ton_kho'] ?>)</span>
                    <?php else: ?>
                        <span style="color: #c0392b; font-weight:600;">Hết hàng</span>
                    <?php endif; ?>
                </span>
            </div>

            <div class="pd-price"><?= number_format($book['gia']) ?>₫</div>

            <div class="pd-desc-short">
                <p><?= mb_substr(strip_tags($book['mo_ta']), 0, 250) ?>...</p>
            </div>

            <div class="pd-actions">
                <button class="btn-add-cart" onclick="addToCart('<?= $book['id_sach'] ?>')">
                    <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                </button>
                <a href="index.php?page=feedback&id=<?= $book['id_sach'] ?>" class="btn-feedback">
                     <i class="fa-solid fa-pen-to-square"></i> Viết đánh giá
                 </a>
                
            </div>
            
            <div class="pd-policy">
                <ul>
                    <li><i class="fa-solid fa-truck-fast"></i> Giao hàng toàn quốc</li>
                    <li><i class="fa-solid fa-rotate-left"></i> Đổi trả trong 7 ngày</li>
                    <li><i class="fa-solid fa-shield-halved"></i> Cam kết chính hãng 100%</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="pd-description">
        <h3>Mô tả sản phẩm</h3>
        <div class="desc-content">
            <?= nl2br($book['mo_ta']) ?>
        </div>
    </div>

    <?php if (!empty($related_books)): ?>
    <div class="pd-related">
        <h3>Có thể bạn sẽ thích</h3>
        <div class="related-grid">
            <?php foreach ($related_books as $rb): ?>
                <div class="related-item">
                    <a href="index.php?page=product&id=<?= $rb['id_sach'] ?>" class="related-img-link">
                        <img src="../admin/uploads/<?= $rb['hinh_anh'] ?>" alt="<?= $rb['ten_sach'] ?>">
                    </a>
                    <div class="related-info">
                        <h4>
                            <a href="index.php?page=product&id=<?= $rb['id_sach'] ?>">
                                <?= $rb['ten_sach'] ?>
                            </a>
                        </h4>
                        <div class="related-price"><?= number_format($rb['gia']) ?>₫</div>
                        
                        <button class="related-btn" onclick="addToCart('<?= $rb['id_sach'] ?>')">
                            Mua ngay
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>