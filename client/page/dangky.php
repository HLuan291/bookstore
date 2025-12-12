<?php
require_once __DIR__ . '/../includes/functions.php';

/* ===============================
   BIẾN THÔNG BÁO
=============================== */
$error   = '';
$success = '';

/* ===============================
   NẾU ĐÃ ĐĂNG NHẬP → VỀ TRANG CHỦ
=============================== */
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

/* ===============================
   XỬ LÝ ĐĂNG KÝ
=============================== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email'] ?? '');
    $mat_khau = $_POST['mat_khau'] ?? '';
    $xac_nhan = $_POST['xac_nhan'] ?? '';
    $ho_ten   = trim($_POST['ho_ten'] ?? '');
    $sdt      = trim($_POST['so_dien_thoai'] ?? '');

    /* 1. VALIDATE */
    if (!$email || !$mat_khau || !$xac_nhan || !$ho_ten || !$sdt) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
    elseif ($mat_khau !== $xac_nhan) {
        $error = "Mật khẩu xác nhận không khớp!";
    }
    else {

        /* 2. KIỂM TRA EMAIL TRÙNG */
        $exists = db_one(
            "SELECT id_nguoidung FROM nguoi_dung WHERE email = :email",
            [':email' => $email]
        );

        if ($exists) {
            $error = "Email đã được sử dụng!";
        }
        else {

            /* 3. TẠO ID NGƯỜI DÙNG (U001…) */
            $lastUser = db_one("
                SELECT id_nguoidung
                FROM nguoi_dung
                ORDER BY id DESC
                LIMIT 1
            ");

            if ($lastUser && preg_match('/U(\d+)/', $lastUser['id_nguoidung'], $m)) {
                $newNumber = intval($m[1]) + 1;
            } else {
                $newNumber = 1;
            }

            $id_nguoidung = 'U' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            /* 4. INSERT NGƯỜI DÙNG */
                    db_execute("
                        INSERT INTO nguoi_dung
                        (id_nguoidung, ho_ten, email, so_dien_thoai, mat_khau, trang_thai, ngay_tao)
                        VALUES
                        (:id, :hoten, :email, :sdt, :mk, 1, NOW())
                    ", [
                        ':id'    => $id_nguoidung,
                        ':hoten' => $ho_ten,
                        ':email' => $email,
                        ':sdt'   => $sdt,
                        ':mk'    => password_hash($mat_khau, PASSWORD_DEFAULT)
                    ]);

                $idVaiTroUser = 'VT03'; // Khách hàng

        db_execute("
            INSERT INTO nguoi_dung_vai_tro
            (id_nguoidungvaitro, id_nguoidung, id_vaitro)
            VALUES
            (:id_ndvt, :id_nd, :id_vt)
        ", [
            ':id_ndvt' => $idNguoiDungVaiTro,
            ':id_nd'   => $id_nguoidung,
            ':id_vt'   => $idVaiTroUser
        ]);

            $success = "Đăng ký thành công! Vui lòng đăng nhập.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>

    <link rel="stylesheet" href="/client/assets/css/dangky.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>

<div class="auth-container">

    <h2 class="auth-title">Đăng ký tài khoản</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="auth-form" autocomplete="off">

        <label>Họ và tên *</label>
        <input type="text" name="ho_ten"
               value="<?= htmlspecialchars($_POST['ho_ten'] ?? '') ?>" required>

        <label>Email *</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <label>Số điện thoại *</label>
        <input type="text" name="so_dien_thoai"
               value="<?= htmlspecialchars($_POST['so_dien_thoai'] ?? '') ?>" required>

        <label>Mật khẩu *</label>
        <input type="password" name="mat_khau" required>

        <label>Xác nhận mật khẩu *</label>
        <input type="password" name="xac_nhan" required>

        <button type="submit" class="btn-auth">Đăng ký</button>

        <div class="auth-links">
            Đã có tài khoản?
            <a href="index.php?page=dangnhap">Đăng nhập ngay</a>
        </div>

    </form>
</div>

</body>
</html>
