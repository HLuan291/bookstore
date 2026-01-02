<?php
$cat_code = isset($_GET['id']) ? $_GET['id'] : "";
$title = ""; $params = []; $is_valid = true;

switch ($page) {
    case 'new': $title = "📚 Sách Mới Cập Nhật"; $sql = "SELECT * FROM sach ORDER BY ngay_tao DESC"; break;
    case 'highlight': $title = "🔥 Sách Đang Hot"; $sql = "SELECT * FROM sach ORDER BY RAND()"; break;
    case 'sale': $title = "🎁 Đại Tiệc Sách - Giảm Giá Sốc"; $sql = "SELECT * FROM sach WHERE gia_giam > 0 AND gia_giam < gia ORDER BY id DESC"; break;
    case 'category':
    default:
        if ($cat_code != "") {
            $category = db_fetch("SELECT ten_danh_muc FROM danh_muc WHERE id_danhmuc = :id", [':id' => $cat_code]);
            if ($category) { $title = "Danh mục: " . htmlspecialchars($category['ten_danh_muc']); $sql = "SELECT * FROM sach WHERE id_danhmuc = :id ORDER BY id_sach DESC"; $params = [':id' => $cat_code]; }
            else { $is_valid = false; }
        } else { $title = "Tất Cả Sách"; $sql = "SELECT * FROM sach ORDER BY id_sach DESC"; }
        break;
}

if (!$is_valid) { echo "<div class='container' style='padding: 50px; text-align: center;'><h3>Danh mục không tồn tại!</h3></div>"; }
else {
    $books = db_fetch_all($sql, $params);
?>
<link rel="stylesheet" href="assets/css/home.css">
<section class="home-section">
    <div class="section-header"><h2 class="section-title"><?= $title ?></h2></div>
    <div class="book-grid">
        <?php if (empty($books)): ?><p style="grid-column: 1/-1; text-align: center;">Hiện chưa có sản phẩm nào.</p>
        <?php else: foreach ($books as $b): ?>
            <div class="book-card">
                <div class="card-img-wrapper">
                    <a href="index.php?page=product&id=<?= $b['id_sach'] ?>"><img src="../admin/uploads/<?= $b['hinh_anh'] ?>"></a>
                    <?php if ($b['gia_giam'] > 0 && $b['gia_giam'] < $b['gia']): ?><span class="badge-sale">Giảm giá</span><?php endif; ?>
                </div>
                <div class="card-info">
                    <h3 class="card-title"><a href="index.php?page=product&id=<?= $b['id_sach'] ?>"><?= htmlspecialchars($b['ten_sach']) ?></a></h3>
                    <div class="card-price">
                        <?php if (isset($b['gia_giam']) && $b['gia_giam'] > 0 && $b['gia_giam'] < $b['gia']): ?>
                            <span class="price-new"><?= number_format($b['gia_giam']) ?>₫</span>
                            <span class="price-old"><?= number_format($b['gia']) ?>₫</span>
                        <?php else: ?>
                            <span class="price-new"><?= number_format($b['gia']) ?>₫</span>
                        <?php endif; ?>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart('<?= $b['id_sach'] ?>')"><i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ</button>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>
<?php } ?>