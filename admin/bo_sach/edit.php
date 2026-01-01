<?php
$pageTitle = "Sửa bộ sách";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("ID bộ sách không hợp lệ");
}

/* ===============================
   LẤY DỮ LIỆU BỘ SÁCH
================================ */
$bosach = db_fetch("
    SELECT * FROM bo_sach
    WHERE id = :id
", [':id' => $id]);

if (!$bosach) {
    die("Không tìm thấy bộ sách");
}

/* ===============================
   LẤY DANH SÁCH SÁCH + DANH MỤC
================================ */
$sachs = db_fetch_all("SELECT id_sach, ten_sach FROM sach ORDER BY ten_sach");
$danhmucs = db_fetch_all("SELECT id_danhmuc, ten_danh_muc FROM danh_muc ORDER BY ten_danh_muc");

$success = "";

/* ===============================
   XỬ LÝ SUBMIT
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_bosach   = trim($_POST['id_bosach']);
    $ten_bo_sach = trim($_POST['ten_bo_sach']);
    $id_sach     = $_POST['id_sach'] ?? null;
    $id_danhmuc  = $_POST['id_danhmuc'] ?? null;
    $gia         = $_POST['gia'] ?? 0;

    db_execute("
        UPDATE bo_sach
        SET id_bosach = :ma,
            ten_bo_sach = :ten,
            id_sach = :sach,
            id_danhmuc = :dm,
            gia = :gia
        WHERE id = :id
    ", [
        ':ma'   => $id_bosach,
        ':ten'  => $ten_bo_sach,
        ':sach' => $id_sach,
        ':dm'   => $id_danhmuc,
        ':gia'  => $gia,
        ':id'   => $id
    ]);

    $success = "✅ Cập nhật bộ sách thành công!";

    // reload dữ liệu
    $bosach = db_fetch("
        SELECT * FROM bo_sach WHERE id = :id
    ", [':id' => $id]);
}
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/bo_sach.css">

<div class="form-wrapper">
    <h1 class="form-title">Sửa Bộ Sách</h1>

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" class="book-form">

        <div class="right-panel">

            <div class="form-row">
                <div class="form-group">
                    <label>Mã bộ sách:</label>
                    <input name="id_bosach" value="<?= htmlspecialchars($bosach['id_bosach']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Tên bộ sách:</label>
                    <input name="ten_bo_sach" value="<?= htmlspecialchars($bosach['ten_bo_sach']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sách:</label>
                    <select name="id_sach">
                        <?php foreach ($sachs as $s): ?>
                            <option value="<?= $s['id_sach'] ?>"
                                <?= $bosach['id_sach'] == $s['id_sach'] ? 'selected' : '' ?>>
                                <?= $s['ten_sach'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Danh mục:</label>
                    <select name="id_danhmuc">
                        <?php foreach ($danhmucs as $d): ?>
                            <option value="<?= $d['id_danhmuc'] ?>"
                                <?= $bosach['id_danhmuc'] == $d['id_danhmuc'] ? 'selected' : '' ?>>
                                <?= $d['ten_danh_muc'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full">
                    <label>Giá:</label>
                    <input type="number" name="gia" value="<?= $bosach['gia'] ?>" required>
                </div>
            </div>

            <div class="form-actions">
                <a href="list.php" class="btn-cancel">Hủy</a>
                <button class="btn-add">Lưu</button>
            </div>

        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
