<?php
$pageTitle = "Chi tiết đơn hàng";
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);

$dh = db_fetch("
    SELECT dh.*, nd.ho_ten, nd.email, nd.sdt
    FROM don_hang dh
    LEFT JOIN nguoi_dung nd ON dh.id_nguoidung = nd.id_nguoidung
    WHERE dh.id = :id
", [":id" => $id]);

if (!$dh) die("Không tìm thấy đơn hàng");

// sản phẩm trong đơn
$items = db_fetch_all("
    SELECT ct.*, s.ten_sach
    FROM chi_tiet_don_hang ct
    LEFT JOIN sach s ON ct.id_sach = s.id_sach
    WHERE ct.id_donhang = :madon
", [":madon" => $dh['id_donhang']]);
?>

<div class="form-page">
<h1>Chi tiết đơn hàng</h1>

<div class="info-box">
    <p><strong>Mã đơn hàng:</strong> <?= $dh['id_donhang'] ?></p>
    <p><strong>Khách hàng:</strong> <?= $dh['ho_ten'] ?></p>
    <p><strong>Email:</strong> <?= $dh['email'] ?></p>
    <p><strong>SĐT:</strong> <?= $dh['sdt'] ?></p>
    <p><strong>Ngày đặt:</strong> <?= $dh['ngay_dat'] ?></p>
    <p><strong>Trạng thái:</strong> <?= $dh['trang_thai'] ?></p>
</div>

<h3>Sản phẩm trong đơn</h3>

<table class="table-admin">
<tr>
    <th>Sách</th>
    <th>Số lượng</th>
    <th>Giá bán</th>
    <th>Thành tiền</th>
</tr>

<?php foreach ($items as $it): ?>
<tr>
    <td><?= $it['ten_sach'] ?></td>
    <td><?= $it['so_luong'] ?></td>
    <td><?= number_format($it['gia_ban'],0,',','.') ?>đ</td>
    <td><?= number_format($it['so_luong'] * $it['gia_ban'],0,',','.') ?>đ</td>
</tr>
<?php endforeach; ?>
</table>

<a href="update.php?id=<?= $id ?>" class="btn-save">Cập nhật trạng thái</a>
<a href="list.php" class="btn-back">Quay lại</a>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
