<?php
$pageTitle = "Thêm bộ sách";
require_once __DIR__ . '/../includes/header.php';

$sachs = db_fetch_all("SELECT * FROM sach");
$danhmucs = db_fetch_all("SELECT * FROM danh_muc");

$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        ':id_bosach' => $_POST['id_bosach'],
        ':id_sach' => $_POST['id_sach'],
        ':id_danhmuc' => $_POST['id_danhmuc'],
        ':ten_bo_sach' => $_POST['ten_bo_sach'],
        ':gia' => $_POST['gia'],
    ];

    db_execute("
        INSERT INTO bo_sach (id_bosach, id_sach, id_danhmuc, ten_bo_sach, gia)
        VALUES (:id_bosach, :id_sach, :id_danhmuc, :ten_bo_sach, :gia)
    ", $data);

    $success = "Thêm bộ sách thành công!";
}
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/bo_sach.css">
<div class="form-page">
<h1>Thêm Bộ Sách</h1>

<?php if ($success): ?>
    <div class="alert success"><?= $success ?></div>
<?php endif; ?>

<form method="post" class="form-admin">

    <label>Mã bộ sách:</label>
    <input name="id_bosach" required>

    <label>Tên bộ sách:</label>
    <input name="ten_bo_sach" required>

    <label>Sách:</label>
    <select name="id_sach">
        <?php foreach ($sachs as $s): ?>
            <option value="<?= $s['id_sach'] ?>"><?= $s['ten_sach'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Danh mục:</label>
    <select name="id_danhmuc">
        <?php foreach ($danhmucs as $d): ?>
            <option value="<?= $d['id_danhmuc'] ?>"><?= $d['ten_danh_muc'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Giá:</label>
    <input type="number" name="gia" required>
        <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button class="btn-add">Lưu</button>
        </div>
</form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
