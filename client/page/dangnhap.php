<?php
require_once __DIR__ . '/../includes/functions.php';

$message = "";

// Nếu user đã đăng nhập → chuyển về trang chủ
if (isset($_SESSION['user'])) {
    header("Location: /client/index.php");
    exit;
}

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $message = "Vui lòng nhập đầy đủ thông tin!";
    } else {

        $user = db_one("
            SELECT * FROM nguoi_dung
            WHERE email = :email
            LIMIT 1
        ", [":email" => $email]);

        if (!$user) {
            $message = "Email không tồn tại!";
        } elseif (!password_verify($password, $user['mat_khau'])) {
            $message = "Mật khẩu không đúng!";
        } else {

            $_SESSION['user'] = [
                "id"    => $user["id_nguoidung"],
                "name"  => $user["ho_ten"],
                "email" => $user["email"]
            ];

            header("Location: /client/index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/client/assets/css/dangnhap.css">
</head>

<body>
<!-- FORM ĐĂNG NHẬP -->
<div class="auth-container">

    <h2 class="auth-title">Đăng nhập tài khoản</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-error"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-form">

        <label>Tài khoản *</label>
        <input type="email" name="email" placeholder="Nhập email" required>

        <label>Mật khẩu *</label>
        <input type="password" name="password" placeholder="Nhập mật khẩu" required>

        <button class="btn-auth">Đăng nhập</button>

        <div class="sub-link">
            <a href="#">Quên mật khẩu?</a>
        </div>

        <div class="auth-links">
            Chưa có tài khoản?
            <a href="index.php?page=dangky">Đăng ký ngay</a>
        </div>

    </form>
</div>

</body>
</html>
