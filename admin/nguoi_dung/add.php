<?php
$pageTitle = "Thêm người dùng";
$current_page = "khach_hang";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';



// Lấy danh sách vai trò
$roles = db_fetch_all("SELECT * FROM vai_tro");

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_nguoidung = trim($_POST['id_nguoidung']);
    $ho_ten       = trim($_POST['ho_ten']);
    $email        = trim($_POST['email']);
    $sdt          = trim($_POST['sdt']);
    $mat_khau     = trim($_POST['mat_khau']);
    $vai_tro      = trim($_POST['vai_tro']);
    $trang_thai   = isset($_POST['trang_thai']) ? 1 : 0;

    if (!$ho_ten || !$email || !$mat_khau) {
        $error = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
    } else {

        // Insert bảng người dùng
        db_run("
            INSERT INTO nguoi_dung (id_nguoidung, ho_ten, email, so_dien_thoai, mat_khau, trang_thai, ngay_tao)
            VALUES (:id_nd, :ht, :email, :sdt, :mk, :tt, NOW())
        ", [
            ':id_nd' => $id_nguoidung,
            ':ht'    => $ho_ten,
            ':email' => $email,
            ':sdt'   => $sdt,
            ':mk'    => password_hash($mat_khau, PASSWORD_BCRYPT),
            ':tt'    => $trang_thai
        ]);

        // Insert vai trò
        db_run("
            INSERT INTO nguoi_dung_vai_tro (id_nguoidung, id_vaitro)
            VALUES (:id_nd, :vt)
        ", [
            ':id_nd' => $id_nguoidung,
            ':vt'    => $vai_tro
        ]);

        $success = "Thêm người dùng thành công!";
    }
}
?>

<link rel="stylesheet" href="../assets/css/nguoi_dung.css">

<div class="form-container">

    <h2>Thêm Khách Hàng Mới</h2>

    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-row">
            <div class="form-group">
                <label>Mã KH</label>
                <input type="text" name="id_nguoidung" placeholder="Nhập mã khách hàng">
            </div>

            <div class="form-group">
                <label>Tài khoản (Email)</label>
                <input type="text" name="email" placeholder="Nhập email đăng nhập">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Họ tên</label>
                <input type="text" name="ho_ten" placeholder="Nhập họ và tên">
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="mat_khau" placeholder="Nhập mật khẩu">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="sdt" placeholder="Nhập số điện thoại">
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <select name="vai_tro">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_vaitro'] ?>"><?= $r['ten_vai_tro'] ?></option>
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
            <button class="btn-submit">+ Thêm</button>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
