<?php
$pageTitle = "Sửa người dùng";
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
$user = db_fetch("SELECT * FROM nguoi_dung WHERE id = :id", [':id' => $id]);

if (!$user) die("Không tìm thấy người dùng");

$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = [
        ':id_nguoidung' => $_POST['id_nguoidung'],
        ':ho_ten' => $_POST['ho_ten'],
        ':email' => $_POST['email'],
        ':sdt' => $_POST['sdt'],
        ':vai_tro' => $_POST['vai_tro'],
        ':trang_thai' => $_POST['trang_thai'],
        ':id' => $id
    ];

    // Nếu người dùng đổi mật khẩu:
    $pass_sql = "";
    if (!empty($_POST['mat_khau'])) {
        $data[':mat_khau'] = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT);
        $pass_sql = ", mat_khau = :mat_khau";
    }

    db_execute("
        UPDATE nguoi_dung SET
            id_nguoidung = :id_nguoidung,
            ho_ten = :ho_ten,
            email = :email,
            sdt = :sdt,
            vai_tro = :vai_tro,
            trang_thai = :trang_thai
            $pass_sql
        WHERE id = :id
    ", $data);

    $success = "Cập nhật thành công!";
}
?>

<div class="form-page">
<h1>Sửa Người Dùng</h1>

<?php if ($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>

<form method="post" class="form-admin">

    <label>Mã người dùng:</label>
    <input name="id_nguoidung" value="<?= $user['id_nguoidung'] ?>">

    <label>Họ tên:</label>
    <input name="ho_ten" value="<?= $user['ho_ten'] ?>">

    <label>Email:</label>
    <input name="email" value="<?= $user['email'] ?>">

    <label>Số điện thoại:</label>
    <input name="sdt" value="<?= $user['sdt'] ?>">

    <label>Vai trò:</label>
    <select name="vai_tro">
        <option <?= $user['vai_tro']=='Admin'?'selected':'' ?> value="Admin">Admin</option>
        <option <?= $user['vai_tro']=='User'?'selected':'' ?> value="User">User</option>
    </select>

    <label>Mật khẩu mới (nếu đổi):</label>
    <input type="password" name="mat_khau">

    <label>Trạng thái:</label>
    <select name="trang_thai">
        <option value="1" <?= $user['trang_thai']==1?'selected':'' ?>>Hoạt động</option>
        <option value="0" <?= $user['trang_thai']==0?'selected':'' ?>>Khóa</option>
    </select>

    <button class="btn-save">Lưu thay đổi</button>
</form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
