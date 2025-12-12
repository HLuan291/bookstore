<?php
require_once __DIR__ . '/../includes/functions.php';
// Nếu user đã đăng nhập → chuyển về trang chủ
if (isset($_SESSION['user'])) {
    header("Location: /client/index.php");
    exit;
}

// Xử lý đăng ký
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $mat_khau = $_POST['mat_khau'];
    $xac_nhan = $_POST['xac_nhan'];
    $ho_ten = trim($_POST['ho_ten']);
    $sdt = trim($_POST['so_dien_thoai']);

    // Kiểm tra rỗng
    if (!$email || !$mat_khau || !$xac_nhan || !$ho_ten || !$sdt) {
        $message = "Vui lòng nhập đầy đủ thông tin!";
    }
    elseif ($mat_khau !== $xac_nhan) {
        $message = "Mật khẩu xác nhận không khớp!";
    }
    else {

        // Kiểm tra email tồn tại
        $check = db_one("SELECT * FROM nguoi_dung WHERE email = :email", [
            ':email' => $email
        ]);

        if ($check) {
            $message = "Email đã được sử dụng!";
        } else {

            // TÌM ID NGƯỜI DÙNG LỚN NHẤT → TẠO MÃ MỚI
            $last = db_one("SELECT id_nguoidung FROM nguoi_dung ORDER BY id DESC LIMIT 1");

            if ($last && preg_match('/U(\d+)/', $last['id_nguoidung'], $m)) {
                $newNumber = intval($m[1]) + 1;
            } else {
                $newNumber = 1;
            }

            $id_nguoidung = "U" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

            // INSERT vào DB
            db_execute("
                INSERT INTO nguoi_dung (id_nguoidung, ho_ten, email, so_dien_thoai, mat_khau, trang_thai, ngay_tao)
                VALUES (:id, :hoten, :email, :sdt, :mk, 1, NOW())
            ", [
                ':id'    => $id_nguoidung,
                ':hoten' => $ho_ten,
                ':email' => $email,
                ':sdt'   => $sdt,
                ':mk'    => password_hash($mat_khau, PASSWORD_DEFAULT)
            ]);

            $message = "Tạo tài khoản thành công! Hãy đăng nhập.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="/client/assets/css/dangnhap.css">
</head>

<body>
<!-- FORM ĐĂNG KÝ -->
<div class="auth-container">

    <h2 class="auth-title">Đăng ký tài khoản</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-error"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-form">

        <label>Họ và tên *</label>
        <input type="text" name="ho_ten" placeholder="Nhập họ tên" required>

        <label>Email *</label>
        <input type="email" name="email" placeholder="Nhập email" required>

        <label>Số điện thoại *</label>
        <input type="text" name="so_dien_thoai" placeholder="Nhập số điện thoại" required>

        <label>Mật khẩu *</label>
        <input type="password" name="mat_khau" placeholder="Nhập mật khẩu" required>

        <label>Xác nhận mật khẩu *</label>
        <input type="password" name="xac_nhan" placeholder="Nhập lại mật khẩu" required>

        <button class="btn-auth">Đăng ký</button>

        <div class="auth-links">
            Đã có tài khoản?
            <a href="index.php?page=dangnhap">Đăng nhập ngay</a>
        </div>

    </form>
</div>

</body>
</html>
