<?php
$pageTitle = "Sửa người dùng";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

/* ===============================
   LẤY ID NGƯỜI DÙNG
=============================== */
$id_nguoidung = trim($_GET['id_nguoidung'] ?? '');

if ($id_nguoidung === '') {
    header("Location: list.php");
    exit;
}

/* ===============================
   LẤY USER
=============================== */
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
   LẤY DANH SÁCH VAI TRÒ
=============================== */
$roles = db_fetch_all("
    SELECT id_vaitro, ten_vai_tro
    FROM vai_tro
    ORDER BY ten_vai_tro
");

/* ===============================
   VAI TRÒ HIỆN TẠI
=============================== */
$currentRole = db_fetch("
    SELECT id_nguoidungvaitro, id_vaitro
    FROM nguoi_dung_vai_tro
    WHERE id_nguoidung = :id
", [':id' => $id_nguoidung]);

$currentRoleId       = $currentRole['id_vaitro'] ?? '';
$id_nguoidungvaitro  = $currentRole['id_nguoidungvaitro'] ?? '';

$error = $success = '';

/* ===============================
   XỬ LÝ SUBMIT
=============================== */
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

        /* CHECK EMAIL TRÙNG */
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

                /* ===== UPDATE NGƯỜI DÙNG ===== */
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

                /* ===== UPDATE VAI TRÒ ===== */
                if ($id_nguoidungvaitro) {
                    // ĐÃ CÓ → UPDATE
                    db_execute("
                        UPDATE nguoi_dung_vai_tro
                        SET id_vaitro = :vt
                        WHERE id_nguoidungvaitro = :ndvt
                    ", [
                        ':vt'   => $vai_tro,
                        ':ndvt' => $id_nguoidungvaitro
                    ]);
                } else {
                    // CHƯA CÓ → INSERT
                    db_execute("
                        INSERT INTO nguoi_dung_vai_tro (id_nguoidung, id_vaitro)
                        VALUES (:id, :vt)
                    ", [
                        ':id' => $id_nguoidung,
                        ':vt' => $vai_tro
                    ]);
                }

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
                $error = "❌ Lỗi hệ thống!";
            }
        }
    }
}
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/nguoi_dung.css">

<div class="form-container">
    <h2>Sửa Người Dùng</h2>

    <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

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
                       value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>">
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
                <button class="btn-submit">Lưu</button>
            </div>

    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
