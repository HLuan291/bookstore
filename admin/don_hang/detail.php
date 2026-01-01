<?php
$pageTitle = "Sửa đơn hàng";
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);

$dh = db_fetch("SELECT * FROM don_hang WHERE id = :id", [':id'=>$id]);
if (!$dh) die("Không tìm thấy đơn hàng");

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $hoten   = trim($_POST['hoten'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $sdt     = trim($_POST['sdt'] ?? '');
    $diachi  = trim($_POST['diachi'] ?? '');
    $trang_thai = $_POST['trang_thai'] ?? '';
    $pttt = $_POST['phuong_thuc_thanh_toan'] ?? '';
    $tttt = $_POST['trang_thai_thanh_toan'] ?? '';

    if ($hoten === '' || $email === '' || $sdt === '' || $diachi === '') {
        $error = "❌ Vui lòng nhập đầy đủ thông tin.";
    } else {
        db_execute("
            UPDATE don_hang SET
                hoten = :hoten,
                email = :email,
                sdt = :sdt,
                diachi = :diachi,
                trang_thai = :tt,
                phuong_thuc_thanh_toan = :pttt,
                trang_thai_thanh_toan = :tttt
            WHERE id = :id
        ", [
            ':hoten' => $hoten,
            ':email' => $email,
            ':sdt' => $sdt,
            ':diachi' => $diachi,
            ':tt' => $trang_thai,
            ':pttt' => $pttt,
            ':tttt' => $tttt,
            ':id' => $id
        ]);

        $success = "✅ Cập nhật đơn hàng thành công!";
        $dh = db_fetch("SELECT * FROM don_hang WHERE id = :id", [':id'=>$id]);
    }
}
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/don_hang.css">
<div class="form-page">
    <h1>Sửa đơn hàng</h1>

    <?php if ($error): ?><div class="alert error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>

    <form method="post" class="form-admin">

        <div class="form-row">
            <div class="form-group">
                <label>Mã đơn hàng</label>
                <input type="text" value="<?= $dh['id_donhang'] ?>" disabled>
            </div>
            <div class="form-group">
                <label>Tổng tiền</label>
                <input type="text" value="<?= number_format($dh['tong_tien']) ?>đ" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Khách hàng</label>
                <input type="text" name="hoten" value="<?= htmlspecialchars($dh['hoten']) ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($dh['email']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt" value="<?= htmlspecialchars($dh['sdt']) ?>">
            </div>
            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="diachi" value="<?= htmlspecialchars($dh['diachi']) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Trạng thái đơn hàng</label>
                <select name="trang_thai">
                    <option <?= $dh['trang_thai']=='Cho xu ly'?'selected':'' ?>>Cho xu ly</option>
                    <option <?= $dh['trang_thai']=='Dang giao'?'selected':'' ?>>Dang giao</option>
                    <option <?= $dh['trang_thai']=='Da giao'?'selected':'' ?>>Da giao</option>
                    <option <?= $dh['trang_thai']=='Huy'?'selected':'' ?>>Huy</option>
                </select>
            </div>

            <div class="form-group">
                <label>Thanh toán</label>
                <select name="trang_thai_thanh_toan">
                    <option <?= $dh['trang_thai_thanh_toan']=='Chưa thanh toán'?'selected':'' ?>>Chưa thanh toán</option>
                    <option <?= $dh['trang_thai_thanh_toan']=='Đã thanh toán'?'selected':'' ?>>Đã thanh toán</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Phương thức thanh toán</label>
                <select name="phuong_thuc_thanh_toan">
                    <option <?= $dh['phuong_thuc_thanh_toan']=='COD'?'selected':'' ?>>COD</option>
                    <option <?= $dh['phuong_thuc_thanh_toan']=='Chuyển khoản'?'selected':'' ?>>Chuyển khoản</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button class="btn-add">Lưu</button>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
