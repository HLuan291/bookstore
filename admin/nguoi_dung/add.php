<?php
$pageTitle = "Thêm người dùng";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

/* ===============================
   LẤY DANH SÁCH VAI TRÒ
================================ */
$roles = db_fetch_all("
    SELECT id_vaitro, ten_vai_tro 
    FROM vai_tro
");

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_nguoidung = trim($_POST['id_nguoidung'] ?? '');
    $ho_ten       = trim($_POST['ho_ten'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $sdt          = trim($_POST['sdt'] ?? '');
    $mat_khau     = $_POST['mat_khau'] ?? '';
    $vai_tro      = $_POST['vai_tro'] ?? '';
    $trang_thai   = isset($_POST['trang_thai']) ? 1 : 0;

    /* ===============================
       VALIDATE
    ================================ */
    if (
        $id_nguoidung === '' ||
        $ho_ten === '' ||
        $email === '' ||
        $mat_khau === '' ||
        $vai_tro === ''
    ) {
        $error = "❌ Vui lòng nhập đầy đủ thông tin bắt buộc.";
    } else {

        /* ===============================
           CHECK TRÙNG USER
        ================================ */
        $exists = db_fetch("
            SELECT id 
            FROM nguoi_dung
            WHERE id_nguoidung = :id_nd
               OR email = :email
        ", [
            ':id_nd' => $id_nguoidung,
            ':email' => $email
        ]);

        if ($exists) {
            $error = "❌ Mã người dùng hoặc Email đã tồn tại!";
        } else {

            try {
                /* ===============================
                   TRANSACTION
                ================================ */
                db()->beginTransaction();

                /* ===============================
                   INSERT NGƯỜI DÙNG
                ================================ */
                db_execute("
                    INSERT INTO nguoi_dung
                    (id_nguoidung, ho_ten, email, so_dien_thoai, mat_khau, trang_thai, ngay_tao)
                    VALUES
                    (:id_nd, :ho_ten, :email, :sdt, :mat_khau, :trang_thai, NOW())
                ", [
                    ':id_nd'      => $id_nguoidung,
                    ':ho_ten'     => $ho_ten,
                    ':email'      => $email,
                    ':sdt'        => $sdt,
                    ':mat_khau'   => password_hash($mat_khau, PASSWORD_DEFAULT),
                    ':trang_thai' => $trang_thai
                ]);

                /* ===============================
                   GÁN VAI TRÒ (KHÔNG DUPLICATE)
                ================================ */
                db_execute("
                    INSERT INTO nguoi_dung_vai_tro (id_nguoidung, id_vaitro)
                    SELECT :id_nd, :id_vt
                    WHERE NOT EXISTS (
                        SELECT 1 
                        FROM nguoi_dung_vai_tro
                        WHERE id_nguoidung = :id_nd
                          AND id_vaitro = :id_vt
                    )
                ", [
                    ':id_nd' => $id_nguoidung,
                    ':id_vt' => $vai_tro
                ]);

                db()->commit();

                $success = "✅ Thêm người dùng thành công!";
                $_POST = [];

            } catch (Exception $e) {
                db()->rollBack();
                $error = "❌ Lỗi hệ thống: " . $e->getMessage();
            }
        }
    }
}
?>

<link rel="stylesheet" href="../assets/css/nguoi_dung.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="form-container">
    <h2>Thêm Người Dùng</h2>

    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-row">
            <div class="form-group">
                <label>Mã người dùng</label>
                <input type="text" name="id_nguoidung"
                       value="<?= $_POST['id_nguoidung'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?= $_POST['email'] ?? '' ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="ho_ten"
                       value="<?= $_POST['ho_ten'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="mat_khau" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt"
                       value="<?= $_POST['sdt'] ?? '' ?>">
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <select name="vai_tro" required>
                    <option value="">-- Chọn vai trò --</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_vaitro'] ?>"
                            <?= (($_POST['vai_tro'] ?? '') == $r['id_vaitro']) ? 'selected' : '' ?>>
                            <?= $r['ten_vai_tro'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Trạng thái</label>
                <label class="switch">
                    <input type="checkbox" name="trang_thai" checked>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

 <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button class="btn-add">Lưu</button>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
