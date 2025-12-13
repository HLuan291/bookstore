<?php
// KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user'])) {
    echo "<script>window.location.href='index.php?page=dangnhap';</script>";
    exit;
}

$userId = $_SESSION['user']['id'];
$message = ''; // Biến lưu thông báo

// ---------------------------------------------------------
// XỬ LÝ 1: UPLOAD AVATAR
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar_file'])) {
    $file = $_FILES['avatar_file'];
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        // Tạo tên file ngẫu nhiên để tránh trùng
        $new_name = "user_" . $userId . "_" . time() . "." . $ext;
        $upload_dir = "assets/uploads/avatars/"; // Thư mục lưu ảnh
        
        // Tạo thư mục nếu chưa có
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
            // Cập nhật vào Database
            db_run("UPDATE nguoi_dung SET avatar = :avt WHERE id_nguoidung = :id", [
                ':avt' => $new_name,
                ':id'  => $userId
            ]);
            $message = "<div class='alert success'>Cập nhật ảnh đại diện thành công!</div>";
        } else {
            $message = "<div class='alert error'>Lỗi khi tải ảnh lên server.</div>";
        }
    } else {
        $message = "<div class='alert error'>Chỉ cho phép file ảnh (JPG, PNG, GIF).</div>";
    }
}

// ---------------------------------------------------------
// XỬ LÝ 2: CẬP NHẬT THÔNG TIN CÁ NHÂN (ĐÃ BỎ ĐỔI PASS)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $ho_ten = trim($_POST['ho_ten']);
    $sdt    = trim($_POST['so_dien_thoai']);
    $dia_chi= trim($_POST['dia_chi']);
    
    // Câu lệnh SQL chỉ cập nhật thông tin cơ bản, không đụng đến mật khẩu
    $sql = "UPDATE nguoi_dung SET ho_ten = :name, so_dien_thoai = :sdt, dia_chi = :dc WHERE id_nguoidung = :id";
    
    $params = [
        ':name' => $ho_ten,
        ':sdt'  => $sdt,
        ':dc'   => $dia_chi,
        ':id'   => $userId
    ];

    if (db_run($sql, $params)) {
        $message = "<div class='alert success'>Cập nhật thông tin thành công!</div>";
        // Cập nhật lại session tên hiển thị
        $_SESSION['user']['ho_ten'] = $ho_ten;
    } else {
        $message = "<div class='alert error'>Có lỗi xảy ra, vui lòng thử lại.</div>";
    }
}

// ---------------------------------------------------------
// LẤY DỮ LIỆU HIỂN THỊ
// ---------------------------------------------------------
// 1. Thông tin người dùng
$user = db_fetch("SELECT * FROM nguoi_dung WHERE id_nguoidung = :id", [':id' => $userId]);

// 2. Lịch sử đơn hàng
$orders = db_fetch_all("
    SELECT dh.*, 
           (SELECT SUM(so_luong) FROM chi_tiet_don_hang WHERE id_donhang = dh.id_donhang) as tong_sp
    FROM don_hang dh
    WHERE dh.id_nguoidung = :uid
    ORDER BY dh.ngay_dat DESC
", [':uid' => $userId]);
?>

<link rel="stylesheet" href="assets/css/profile.css">

<div class="profile-container">
    
    <?= $message ?>

    <div class="profile-layout">
        
        <div class="profile-sidebar">
            <div class="avatar-wrapper">
                <?php 
                    // Kiểm tra xem có avatar không, nếu không dùng ảnh mặc định
                    $avatar_path = !empty($user['avatar']) ? "assets/uploads/avatars/" . $user['avatar'] : "assets/img/default-avatar.jpg";
                ?>
                <img src="<?= $avatar_path ?>" alt="Avatar" class="profile-avatar" id="avatar-preview">
                
                <form action="" method="POST" enctype="multipart/form-data" class="upload-form">
                    <label for="avatar-input" class="camera-icon" title="Đổi ảnh đại diện">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                    <input type="file" name="avatar_file" id="avatar-input" accept="image/*" onchange="this.form.submit()" style="display: none;">
                </form>
            </div>

            <h3 class="sidebar-name"><?= htmlspecialchars($user['ho_ten']) ?></h3>
            <p class="sidebar-email"><?= htmlspecialchars($user['email']) ?></p>

            <div class="sidebar-menu">
                <button class="menu-btn active" onclick="switchTab('info')">
                    <i class="fa-regular fa-user"></i> Thông tin cá nhân
                </button>
                <button class="menu-btn" onclick="switchTab('orders')">
                    <i class="fa-solid fa-clock-rotate-left"></i> Lịch sử đơn hàng
                </button>
                <a href="index.php?page=logout" class="menu-btn logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>
        </div>

        <div class="profile-content">
            
            <div id="tab-info" class="tab-content active">
                <h2 class="content-title">Hồ sơ của tôi</h2>
                <form action="" method="POST" class="edit-form">
                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="ho_ten" value="<?= htmlspecialchars($user['ho_ten']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email (Không thể thay đổi)</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background: #f5f5f5; cursor: not-allowed;">
                    </div>

                    <div class="form-group-row">
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>" placeholder="Cập nhật SĐT">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <input type="text" name="dia_chi" value="<?= htmlspecialchars($user['dia_chi'] ?? '') ?>" placeholder="Cập nhật địa chỉ">
                        </div>
                    </div>

                    <button type="submit" name="update_profile" class="btn-save">Lưu thay đổi</button>
                </form>
            </div>

            <div id="tab-orders" class="tab-content">
                <h2 class="content-title">Đơn hàng của bạn</h2>
                
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" alt="Empty" style="width: 80px; opacity: 0.5;">
                        <p>Bạn chưa có đơn hàng nào.</p>
                        <a href="index.php" class="btn-shop">Mua sắm ngay</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td><strong>#<?= $o['id_donhang'] ?></strong></td>
                                    <td><?= date('d/m/Y', strtotime($o['ngay_dat'])) ?></td>
                                    <td style="text-align: center;"><?= $o['tong_sp'] ?></td>
                                    <td style="color: #d32f2f; font-weight: bold;"><?= number_format($o['tong_tien']) ?>đ</td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $o['trang_thai'])) ?>">
                                            <?= $o['trang_thai'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.menu-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');
        event.currentTarget.classList.add('active');
    }
</script>