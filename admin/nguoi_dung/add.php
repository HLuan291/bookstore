<?php
$pageTitle = "Thêm người dùng";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';
$success = '';

/* ===============================
   LẤY DANH SÁCH VAI TRÒ
=============================== */
$roles = db_fetch_all("
    SELECT id_vaitro, ten_vai_tro
    FROM vai_tro
    ORDER BY ten_vai_tro
");

/* ===============================
   HÀM TỰ SINH MÃ (VIẾT TRONG FILE)
=============================== */
function autoCode(string $table, string $column, string $prefix, int $pad = 4): string
{
    $row = db_fetch("
        SELECT $column
        FROM $table
        WHERE $column LIKE :pre
        ORDER BY id DESC
        LIMIT 1
    ", [
        ':pre' => $prefix . '%'
    ]);

    $num = 1;
    if ($row && preg_match('/' . preg_quote($prefix, '/') . '(\d+)/', $row[$column], $m)) {
        $num = (int)$m[1] + 1;
    }

    return $prefix . str_pad($num, $pad, '0', STR_PAD_LEFT);
}

/* ===============================
   SUBMIT
=============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ho_ten     = trim($_POST['ho_ten'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $sdt        = trim($_POST['sdt'] ?? '');
    $mat_khau   = $_POST['mat_khau'] ?? '';
    $vai_tro    = $_POST['vai_tro'] ?? '';
    $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

    if ($ho_ten === '' || $email === '' || $mat_khau === '' || $vai_tro === '') {
        $error = "❌ Vui lòng nhập đầy đủ thông tin bắt buộc.";
    } else {

        /* CHECK EMAIL TRÙNG */
        $exists = db_fetch("
            SELECT id
            FROM nguoi_dung
            WHERE email = :email
        ", [
            ':email' => $email
        ]);

        if ($exists) {
            $error = "❌ Email đã tồn tại!";
        } else {

            try {
                db()->beginTransaction();

                /* === TỰ SINH ID === */
                $id_nguoidung = autoCode('nguoi_dung', 'id_nguoidung', 'U', 5);
                $id_nguoidungvaitro = autoCode('nguoi_dung_vai_tro', 'id_nguoidungvaitro', 'NDVT', 2);

                /* === INSERT NGƯỜI DÙNG === */
                db_execute("
                    INSERT INTO nguoi_dung
                        (id_nguoidung, ho_ten, email, so_dien_thoai, mat_khau, trang_thai, ngay_tao)
                    VALUES
                        (:id, :ten, :email, :sdt, :mk, :tt, NOW())
                ", [
                    ':id'    => $id_nguoidung,
                    ':ten'   => $ho_ten,
                    ':email' => $email,
                    ':sdt'   => $sdt,
                    ':mk'    => password_hash($mat_khau, PASSWORD_DEFAULT),
                    ':tt'    => $trang_thai
                ]);

                /* === INSERT VAI TRÒ === */
                db_execute("
                    INSERT INTO nguoi_dung_vai_tro
                        (id_nguoidungvaitro, id_nguoidung, id_vaitro)
                    VALUES
                        (:ndvt, :nd, :vt)
                ", [
                    ':ndvt' => $id_nguoidungvaitro,
                    ':nd'   => $id_nguoidung,
                    ':vt'   => $vai_tro
                ]);

                db()->commit();

                $success = "✅ Thêm người dùng thành công!";
                $_POST = [];

            } catch (Exception $e) {
                db()->rollBack();
                $error = "❌ Lỗi hệ thống!";
                // DEBUG tạm nếu cần:
                // die($e->getMessage());
            }
        }
    }
}
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/nguoi_dung.css">

<div class="form-container">
    <h2>Thêm Người Dùng</h2>

    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-row">
            <div class="form-group">
                <label>Mã người dùng</label>
                <input type="text" value="(Tự sinh)" disabled>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="ho_ten"
                       value="<?= htmlspecialchars($_POST['ho_ten'] ?? '') ?>" required>
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
                       value="<?= htmlspecialchars($_POST['sdt'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <select name="vai_tro" required>
                    <option value="">-- Chọn vai trò --</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_vaitro'] ?>"
                            <?= (($_POST['vai_tro'] ?? '') === $r['id_vaitro']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['ten_vai_tro']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <label class="switch">
                <input type="checkbox" name="trang_thai" checked>
                <span class="slider round"></span>
            </label>
            <span>Hoạt động</span>
        </div>

   <div class="form-actions">
                <a href="list.php" class="btn-cancel">Hủy</a>
                <button class="btn-submit">Lưu</button>
            </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
