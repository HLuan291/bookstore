<?php
session_start();
require_once __DIR__ . '/includes/functions.php'; 

// --- LOGIC PHP (GIỮ NGUYÊN) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $current_page = $_SERVER['PHP_SELF'];

    if (!$email || !$password) {
        header("Location: $current_page?error=Vui lòng nhập đầy đủ thông tin!");
        exit;
    }
    $user = db_fetch("SELECT * FROM nguoi_dung WHERE email = :email", [':email' => $email]);

    if (!$user || !password_verify($password, $user['mat_khau'])) {
        header("Location: $current_page?error=Tài khoản hoặc mật khẩu không đúng!");
        exit;
    }
    if ($user['trang_thai'] == 0) {
        header("Location: $current_page?error=Tài khoản đã bị khóa!");
        exit;
    }
    $_SESSION['admin'] = [
        'id' => $user['id_nguoidung'],
        'name' => $user['ho_ten'],
        'email' => $user['email']
    ];
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Bookstore</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                
                <div class="card card-login o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            <div class="col-lg-6 d-none d-lg-flex bg-login-image py-5">
                                <div class="text-center">
                                    <i class="fas fa-book-open fa-4x mb-3 text-warning"></i>
                                    <h2 class="fw-bold ls-2">BOOKSTORE</h2>
                                    <p class="small text-white-50">Admin Management System</p>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900 fw-bold">Welcome Back!</h1>
                                        <p class="text-muted small">Login to access your dashboard</p>
                                    </div>

                                    <?php if (!empty($_GET['error'])): ?>
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <div><?= htmlspecialchars($_GET['error']) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <form class="user" method="post" action="">
                                        
                                        <div class="form-group mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck" name="remember">
                                                <label class="form-check-label small" for="customCheck">Remember Me</label>
                                            </div>
                                            <a href="#" class="small link-orange">Forgot Password?</a>
                                        </div>

                                        <button type="submit" class="btn btn-orange w-100 mb-3">
                                            LOGIN
                                        </button>
                                        
                                        <div class="text-center mb-3">
                                            <span class="separator-text">OR</span>
                                            <div class="separator-line"></div>
                                        </div>

                                        <a href="dangky.php" class="btn btn-outline-orange w-100">
                                            Create an Account!
                                        </a>

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