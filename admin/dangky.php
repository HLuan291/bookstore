<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

// --- PHẦN XỬ LÝ PHP (FIX LỖI) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $pass     = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    
    // 1. Validate dữ liệu
    if (!$fullname || !$email || !$pass || !$confirm) {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    } elseif ($pass !== $confirm) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // 2. Kiểm tra Email đã tồn tại chưa
        $exists = db_fetch("SELECT * FROM nguoi_dung WHERE email = :e", [":e" => $email]);
        
        if ($exists) {
            $error = "Email này đã được sử dụng!";
        } else {
            // 3. TẠO ID (Fix lỗi độ dài char 10)
            // Tạo ID ngẫu nhiên: U + 6 số (Ví dụ: U123456)
            $id_user = 'U' . rand(100000, 999999);
            
            // 4. INSERT VÀO BẢNG nguoi_dung (Bỏ cột vai_tro vì bảng không có)
            // Mặc định trạng thái = 1 (Hoạt động)
            $sql_user = "INSERT INTO nguoi_dung (id_nguoidung, ho_ten, email, mat_khau, so_dien_thoai, trang_thai) 
                         VALUES (:id, :name, :email, :pass, '', 1)";
            
            db_execute($sql_user, [
                ":id"    => $id_user,
                ":name"  => $fullname,
                ":email" => $email,
                ":pass"  => password_hash($pass, PASSWORD_DEFAULT)
            ]);

            // 5. INSERT VÀO BẢNG nguoi_dung_vai_tro (QUAN TRỌNG)
            // Mặc định tạo tài khoản là ADMIN (VT01) để bạn test, hoặc Khách hàng (VT03)
            // Ở đây tôi set là VT01 (Admin) vì bạn đang làm trang Admin.
            $id_ndvt = 'UR' . rand(10000, 99999); // Tạo ID cho bảng trung gian
            
            $sql_role = "INSERT INTO nguoi_dung_vai_tro (id_nguoidungvaitro, id_nguoidung, id_vaitro)
                         VALUES (:id_row, :id_u, 'VT01')";
            
            db_execute($sql_role, [
                ':id_row' => $id_ndvt,
                ':id_u'   => $id_user
            ]);

            // Đăng ký thành công -> Chuyển về đăng nhập
            header("Location: dangnhap.php?success=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css"> </head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                
                <div class="card card-login o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            <div class="col-lg-6 d-none d-lg-flex bg-login-image py-5">
                                <div class="text-center">
                                    <i class="fas fa-user-plus fa-4x mb-3 text-warning"></i>
                                    <h2 class="fw-bold ls-2">JOIN US</h2>
                                    <p class="small text-white-50">Create your admin account</p>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900 fw-bold">Create an Account!</h1>
                                    </div>

                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div><?= htmlspecialchars($error) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <form class="user" method="post" action="">
                                        
                                        <div class="form-group mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" name="fullname" placeholder="Full Name" required value="<?= isset($fullname) ? htmlspecialchars($fullname) : '' ?>">
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" name="email" placeholder="Email Address" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                                <input type="password" class="form-control" name="confirm" placeholder="Repeat Password" required>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-orange w-100 mb-3">
                                            REGISTER ACCOUNT
                                        </button>
                                        
                                        <div class="text-center mb-3">
                                            <span class="separator-text">OR</span>
                                            <div class="separator-line"></div>
                                        </div>

                                        <div class="text-center">
                                            <a class="small link-orange" href="dangnhap.php">Already have an account? Login!</a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>