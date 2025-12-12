<?php
$pageTitle = "Cập nhật đơn hàng";
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);

$dh = db_fetch("SELECT * FROM don_hang WHERE id = :id", [':id'=>$id]);
if (!$dh) die("Không tìm thấy đơn hàng");

$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    db_execute("
        UPDATE don_hang SET trang_thai = :tt WHERE id = :id
    ", [
        ':tt' => $_POST['trang_thai'],
        ':id' => $id
    ]);

    $success = "Cập nhật thành công!";
}
?>

<div class="form-page">
<h1>Cập nhật trạng thái đơn hàng</h1>

<?php if ($success): ?>
<div class="alert success"><?= $success ?></div>
<?php endif; ?>

<form method="post" class="form-admin">

    <label>Trạng thái đơn hàng:</label>
    <select name="trang_thai">
        <option <?= $dh['trang_thai']=="Cho xu ly"?"selected":"" ?> value="Cho xu ly">Chờ xử lý</option>
        <option <?= $dh['trang_thai']=="Dang giao"?"selected":"" ?> value="Dang giao">Đang giao</option>
        <option <?= $dh['trang_thai']=="Da giao"?"selected":"" ?> value="Da giao">Đã giao</option>
        <option <?= $dh['trang_thai']=="Huy"?"selected":"" ?> value="Huy">Hủy</option>
    </select>

    <button class="btn-save">Lưu thay đổi</button>
</form>

<a href="detail.php?id=<?= $id ?>" class="btn-back">Quay lại</a>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
