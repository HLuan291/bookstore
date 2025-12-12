<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';

$message = "";

/* XỬ LÝ KHI NGƯỜI DÙNG BẤM ĐĂNG NHẬP */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $pass  = $_POST["password"];

    // Lấy thông tin người dùng
    $user = db_fetch("
        SELECT * FROM nguoi_dung 
        WHERE email = :email OR id_nguoidung = :email
    ", [
        ":email" => $email
    ]);

    if (!$user) {
        $message = "❌ Tài khoản không tồn tại!";
    } 
    elseif (!password_verify($pass, $user["mat_khau"])) {
        $message = "❌ Mật khẩu không đúng!";
    } 
    elseif ($user["trang_thai"] == 0) {
        $message = "❌ Tài khoản của bạn đang bị khóa!";
    } 
    else {
        // Lưu session
        $_SESSION["user"] = [
            "id" => $user["id_nguoidung"],
            "name" => $user["ho_ten"],
            "email" => $user["email"]
        ];

        // Chuyển về trang chủ
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập tài khoản</title>
    <link rel="stylesheet" href="/client/assets/css/auth.css">
</head>

<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="auth-container">
    <h2 class="auth-title">Đăng nhập tài khoản</h2>

    <?php if ($message): ?>
        <div class="alert alert-error"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-form">

        <label>Tài khoản hoặc Email</label>
        <input type="text" name="email" placeholder="Nhập tài khoản hoặc email" required>

        <label>Mật khẩu</label>
        <input type="password" name="password" placeholder="Nhập mật khẩu" required>

        <button class="btn-auth">Đăng nhập</button>

        <div class="auth-links">
            <a href="#">Quên mật khẩu?</a>
            <span> | </span>
            <a href="register.php">Chưa có tài khoản? Đăng ký ngay.</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
