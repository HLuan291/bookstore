<?php
require_once __DIR__ . '/../includes/header.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_danhmuc   = trim($_POST['id_danhmuc']);
    $ten_danh_muc = trim($_POST['ten_danh_muc']);
    $trang_thai   = $_POST['trang_thai'];

    // Kiểm tra rỗng
    if (!$id_danhmuc || !$ten_danh_muc) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } else {

        // Kiểm tra trùng mã danh mục
        $check = db_fetch(
            "SELECT id_danhmuc FROM danh_muc WHERE id_danhmuc = :id",
            [':id' => $id_danhmuc]
        );

        if ($check) {
            $error = "Mã danh mục đã tồn tại, vui lòng nhập mã khác!";
        } else {
            db_execute("
                INSERT INTO danh_muc (id_danhmuc, ten_danh_muc, trang_thai)
                VALUES (:id, :ten, :tt)
            ", [
                ':id'  => $id_danhmuc,
                ':ten' => $ten_danh_muc,
                ':tt'  => $trang_thai
            ]);

            $success = "Thêm danh mục thành công!";
        }
    }
}
?>

<link rel="stylesheet" href="../assets/css/danh_muc.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="form-page">
    <div class="form-box">

        <h2 class="form-title">Thêm Danh Mục</h2>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label>Mã danh mục</label>
                <input type="text" name="id_danhmuc" placeholder="VD: DM01" required>
            </div>

            <div class="form-group">
                <label>Tên danh mục</label>
                <input type="text" name="ten_danh_muc" placeholder="VD: Sách giáo khoa" required>
            </div>

            <div class="form-group">
                <label>Trạng thái</label>
                <select name="trang_thai">
                    <option value="1">Hoạt động</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

 <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button class="btn-add">Lưu</button>
        </div>

        </form>

    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
