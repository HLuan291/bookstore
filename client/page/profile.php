<?php
// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=dangnhap");
    exit;
}

$userId = $_SESSION['user']['id'];

// Lấy thông tin người dùng
$user = db_fetch("
    SELECT * FROM nguoi_dung
    WHERE id_nguoidung = :id
", [':id' => $userId]);

if (!$user) {
    die("Không tìm thấy người dùng!");
}

// Lấy danh sách đơn hàng
$orders = db_fetch_all("
    SELECT dh.*, 
           (SELECT SUM(ct.so_luong) FROM chi_tiet_don_hang ct WHERE ct.id_donhang = dh.id_donhang) AS tong_sp,
           (SELECT SUM(ct.so_luong * s.gia) 
                FROM chi_tiet_don_hang ct 
                JOIN sach s ON s.id_sach = ct.id_sach
                WHERE ct.id_donhang = dh.id_donhang) AS tong_tien
    FROM don_hang dh
    WHERE dh.id_nguoidung = :uid
    ORDER BY dh.ngay_dat DESC
", [':uid' => $userId]);

?>
<link rel="stylesheet" href="assets/css/profile.css">

<div class="profile-container">

    <h2 class="profile-title">Thông tin cá nhân</h2>

    <div class="profile-info">

        <p><strong>Họ tên:</strong> <?= htmlspecialchars($user['ho_ten']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['so_dien_thoai'] ?? 'Chưa cập nhật') ?></p>
        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['dia_chi'] ?? 'Chưa cập nhật') ?></p>

        <a class="btn-edit" href="index.php?page=edit_profile">Chỉnh sửa thông tin</a>
    </div>

    <hr>

    <h2 class="profile-title">Lịch sử đơn hàng</h2>

    <?php if (empty($orders)): ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php else: ?>

    <table class="order-table">
        <tr>
            <th>Mã đơn</th>
            <th>Ngày đặt</th>
            <th>Số lượng SP</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
        </tr>

        <?php foreach ($orders as $o): ?>
        <tr>
            <td>#<?= $o['id_donhang'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($o['ngay_dat'])) ?></td>
            <td><?= $o['tong_sp'] ?></td>
            <td><?= number_format($o['tong_tien']) ?>đ</td>
            <td>
                <span class="status <?= strtolower($o['trang_thai']) ?>">
                    <?= $o['trang_thai'] ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

    <?php endif; ?>

</div>

