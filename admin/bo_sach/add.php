<?php
$pageTitle = "Thêm bộ sách";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

/* ===============================
   LẤY DỮ LIỆU
================================ */
$sachs = db_fetch_all("SELECT * FROM sach");
$danhmucs = db_fetch_all("SELECT * FROM danh_muc");

$success = "";
$error   = "";

/* ===============================
   AUTO SINH MÃ BỘ SÁCH
================================ */
$row = db_fetch("SELECT MAX(id_bosach) AS max_ma FROM bo_sach");

$nextNumber = 1;
if ($row && $row['max_ma']) {
    // Cắt "BS" lấy số phía sau
    $nextNumber = (int)substr($row['max_ma'], 2) + 1;
}

$id_bosach_auto = 'BS' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

/* ===============================
   XỬ LÝ SUBMIT
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $ten_bo_sach = trim($_POST['ten_bo_sach']);
    $id_sach     = $_POST['id_sach'];
    $id_danhmuc  = $_POST['id_danhmuc'];
    $gia         = $_POST['gia'];

    if ($ten_bo_sach === "" || $gia === "") {
        $error = "❌ Vui lòng nhập đầy đủ thông tin!";
    } else {

        db_execute("
            INSERT INTO bo_sach (id_bosach, id_sach, id_danhmuc, ten_bo_sach, gia)
            VALUES (:id_bosach, :id_sach, :id_danhmuc, :ten_bo_sach, :gia)
        ", [
            ':id_bosach'   => $id_bosach_auto,
            ':id_sach'     => $id_sach,
            ':id_danhmuc'  => $id_danhmuc,
            ':ten_bo_sach' => $ten_bo_sach,
            ':gia'         => $gia
        ]);

        $success = "✅ Thêm bộ sách thành công! (Mã: $id_bosach_auto)";
        
        // sinh sẵn mã mới cho lần thêm tiếp theo
        $nextNumber++;
        $id_bosach_auto = 'BS' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        $_POST = [];
    }
}
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/bo_sach.css">

<div class="form-page">
    <h1>Thêm Bộ Sách</h1>

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" class="form-admin">

        <div class="form-row">
            <div class="form-group">
                <label>Mã bộ sách</label>
                <input type="text" value="<?= $id_bosach_auto ?>" disabled>
            </div>

            <div class="form-group">
                <label>Tên bộ sách</label>
                <input type="text" name="ten_bo_sach"
                       value="<?= $_POST['ten_bo_sach'] ?? '' ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Sách</label>
                <select name="id_sach">
                    <?php foreach ($sachs as $s): ?>
                        <option value="<?= $s['id_sach'] ?>">
                            <?= htmlspecialchars($s['ten_sach']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Danh mục</label>
                <select name="id_danhmuc">
                    <?php foreach ($danhmucs as $d): ?>
                        <option value="<?= $d['id_danhmuc'] ?>">
                            <?= htmlspecialchars($d['ten_danh_muc']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Giá</label>
                <input type="number" name="gia"
                       value="<?= $_POST['gia'] ?? '' ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button type="submit" class="btn-add">Lưu</button>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
