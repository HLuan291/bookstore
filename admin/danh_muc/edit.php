<?php
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID danh mục không hợp lệ!");
}

// Lấy dữ liệu theo khóa chính "id"
$data = db_fetch("
    SELECT * FROM danh_muc WHERE id = :id
", [':id' => $id]);

if (!$data) {
    die("Không tìm thấy danh mục!");
}

$error = "";
$success = "";

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ma = trim($_POST['id_danhmuc']);
    $ten = trim($_POST['ten_danh_muc']);
    $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

    if ($ma == "" || $ten == "") {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } else {

        db_execute("
            UPDATE danh_muc
            SET id_danhmuc = :ma,
                ten_danh_muc = :ten,
                trang_thai   = :tt
            WHERE id = :id
        ", [
            ':ma'  => $ma,
            ':ten' => $ten,
            ':tt'  => $trang_thai,
            ':id'  => $id
        ]);

        $success = "Cập nhật danh mục thành công!";
        
        // Cập nhật dữ liệu hiển thị lại form
        $data['id_danhmuc'] = $ma;
        $data['ten_danh_muc'] = $ten;
        $data['trang_thai'] = $trang_thai;
    }
}
?>

<link rel="stylesheet" href="../assets/css/danh_muc.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="page-header">
    <h1>Chỉnh Sửa Danh Mục</h1>
</div>

<div class="form-box">

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">

        <label>Mã danh mục:</label>
        <input type="text" name="id_danhmuc" value="<?= htmlspecialchars($data['id_danhmuc']) ?>">

        <label>Tên danh mục:</label>
        <input type="text" name="ten_danh_muc" value="<?= htmlspecialchars($data['ten_danh_muc']) ?>" required>

        <label>Trạng thái:</label>
        <select name="trang_thai">
            <option value="1" <?= $data['trang_thai'] == 1 ? "selected" : "" ?>>Hoạt động</option>
            <option value="0" <?= $data['trang_thai'] == 0 ? "selected" : "" ?>>Ẩn</option>
        </select>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button class="btn-add">Lưu</button>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
