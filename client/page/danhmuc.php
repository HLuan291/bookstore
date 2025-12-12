<?php
$cate = db_fetch_all("SELECT * FROM danh_muc");
$books = [];

if (!empty($_GET['dm'])) {
    $books = db_fetch_all("SELECT * FROM sach WHERE id_danhmuc = :dm", [
        ":dm" => $_GET['dm']
    ]);
}
?>

<h2>Danh mục sách</h2>

<div class="category-list">
    <?php foreach ($cate as $c): ?>
        <a class="cate-item" href="index.php?page=category&dm=<?= $c['id_danhmuc'] ?>">
            <?= $c['ten_danh_muc'] ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="product-grid">
<?php foreach ($books as $b): ?>
    <div class="product-card">
        <img src="../uploads/<?= $b['hinh_anh'] ?>">
        <h3><?= $b['ten_sach'] ?></h3>
        <p><?= number_format($b['gia']) ?>đ</p>
        <a class="btn" href="index.php?page=product&id=<?= $b['id_sach'] ?>">Xem chi tiết</a>
    </div>
<?php endforeach; ?>
</div>
