<?php
$pageTitle = "Sửa người dùng";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

/* ===============================
   LẤY ID NGƯỜI DÙNG (CHAR)
================================ */
$id_nguoidung = trim($_GET['id_nguoidung'] ?? '');

if ($id_nguoidung === '') {
    header("Location: list.php");
    exit;
}

/* ===============================
   LẤY USER
================================ */
$user = db_fetch("
    SELECT *
    FROM nguoi_dung
    WHERE id_nguoidung = :id
", [':id' => $id_nguoidung]);

if (!$user) {
    header("Location: list.php?msg=notfound");
    exit;
}

/* ===============================
   LẤY VAI TRÒ
================================ */
$roles = db_fetch_all("
    SELECT id_vaitro, ten_vai_tro
    FROM vai_tro
");

$currentRole = db_fetch("
    SELECT id_vaitro
    FROM nguoi_dung_vai_tro
    WHERE id_nguoidung = :id
", [':id' => $id_nguoidung]);

$currentRoleId = $currentRole['id_vaitro'] ?? '';

$error = $success = '';

/* ===============================
   XỬ LÝ SUBMIT
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $ho_ten     = trim($_POST['ho_ten'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $sdt        = trim($_POST['so_dien_thoai'] ?? '');
    $vai_tro    = $_POST['id_vaitro'] ?? '';
    $mat_khau   = $_POST['mat_khau'] ?? '';
    $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

    if ($ho_ten === '' || $email === '' || $vai_tro === '') {
        $error = "❌ Vui lòng nhập đầy đủ thông tin bắt buộc.";
    } else {

        $exists = db_fetch("
            SELECT id
            FROM nguoi_dung
            WHERE email = :email
              AND id_nguoidung <> :id
        ", [
            ':email' => $email,
            ':id'    => $id_nguoidung
        ]);

        if ($exists) {
            $error = "❌ Email đã tồn tại.";
        } else {
            try {
                db()->beginTransaction();

                /* UPDATE USER */
                $sql = "
                    UPDATE nguoi_dung
                    SET ho_ten = :ho_ten,
                        email = :email,
                        so_dien_thoai = :sdt,
                        trang_thai = :trang_thai
                ";

                $params = [
                    ':ho_ten'     => $ho_ten,
                    ':email'      => $email,
                    ':sdt'        => $sdt,
                    ':trang_thai' => $trang_thai,
                    ':id'         => $id_nguoidung
                ];

                if ($mat_khau !== '') {
                    $sql .= ", mat_khau = :mat_khau";
                    $params[':mat_khau'] = password_hash($mat_khau, PASSWORD_DEFAULT);
                }

                $sql .= " WHERE id_nguoidung = :id";

                db_execute($sql, $params);

                /* UPDATE ROLE */
                db_execute("
                    DELETE FROM nguoi_dung_vai_tro
                    WHERE id_nguoidung = :id
                ", [':id' => $id_nguoidung]);

                db_execute("
                    INSERT INTO nguoi_dung_vai_tro (id_nguoidung, id_vaitro)
                    VALUES (:id, :vt)
                ", [
                    ':id' => $id_nguoidung,
                    ':vt' => $vai_tro
                ]);

                db()->commit();
                $success = "✅ Cập nhật thành công!";
                $currentRoleId = $vai_tro;

                // reload user
                $user = db_fetch("
                    SELECT *
                    FROM nguoi_dung
                    WHERE id_nguoidung = :id
                ", [':id' => $id_nguoidung]);

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
    <h2>Sửa Người Dùng</h2>

    <?php if ($error): ?><div class="alert error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>

    <form method="POST">

        <div class="form-row">
            <div class="form-group">
                <label>Mã người dùng</label>
                <input type="text" value="<?= htmlspecialchars($user['id_nguoidung']) ?>" disabled>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="ho_ten"
                       value="<?= htmlspecialchars($user['ho_ten']) ?>" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu mới</label>
                <input type="password" name="mat_khau"
                       placeholder="Để trống nếu không đổi">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="so_dien_thoai"
                       value="<?= htmlspecialchars($user['so_dien_thoai']) ?>">
            </div>
            <div class="form-group">
                <label>Vai trò</label>
                <select name="id_vaitro" required>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_vaitro'] ?>"
                            <?= ($currentRoleId == $r['id_vaitro']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['ten_vai_tro']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <label class="switch">
                <input type="checkbox" name="trang_thai"
                    <?= ($user['trang_thai'] == 1) ? 'checked' : '' ?>>
                <span class="slider round"></span>
            </label>
            <span>Hoạt động</span>
        </div>

 <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button class="btn-add">Lưu</button>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
