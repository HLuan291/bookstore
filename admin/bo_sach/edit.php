<?php
$pageTitle = "Sửa bộ sách";
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET["id"] ?? 0);
$bosach = db_fetch("SELECT * FROM bo_sach WHERE id = :id", [':id' => $id]);

if (!$bosach) die("Không tìm thấy bộ sách");

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
        ':id' => $id
    ];

    db_execute("
        UPDATE bo_sach SET
            id_bosach = :id_bosach,
            id_sach = :id_sach,
            id_danhmuc = :id_danhmuc,
            ten_bo_sach = :ten_bo_sach,
            gia = :gia
        WHERE id = :id
    ", $data);

    $success = "Cập nhật thành công!";
}
?>

<div class="form-page">
<h1>Sửa Bộ Sách</h1>

<?php if ($success): ?>
    <div class="alert success"><?= $success ?></div>
<?php endif; ?>

<form method="post" class="form-admin">

    <label>Mã bộ sách:</label>
    <input name="id_bosach" value="<?= $bosach['id_bosach'] ?>">

    <label>Tên bộ sách:</label>
    <input name="ten_bo_sach" value="<?= $bosach['ten_bo_sach'] ?>">

    <label>Sách:</label>
    <select name="id_sach">
        <?php foreach ($sachs as $s): ?>
            <option value="<?= $s['id_sach'] ?>" <?= $bosach['id_sach'] == $s['id_sach'] ? 'selected' : '' ?>>
                <?= $s['ten_sach'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Danh mục:</label>
    <select name="id_danhmuc">
        <?php foreach ($danhmucs as $d): ?>
            <option value="<?= $d['id_danhmuc'] ?>" <?= $bosach['id_danhmuc'] == $d['id_danhmuc'] ? 'selected' : '' ?>>
                <?= $d['ten_danh_muc'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Giá:</label>
    <input name="gia" type="number" value="<?= $bosach['gia'] ?>">

    <button class="btn-save">Lưu thay đổi</button>
</form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
