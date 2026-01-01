<?php
// FILE: page/checkout.php

// 1. Kiểm tra giỏ hàng
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Giỏ hàng đang trống!'); window.location.href='index.php';</script>";
    exit();
}

// Lấy lại danh sách sách để hiển thị ở trang thanh toán
$cart_items = [];
$total_money = 0;
$ids = array_keys($_SESSION['cart']);

if (!empty($ids)) {
    // Truy vấn dữ liệu sách
    $ids_str = "'" . implode("','", $ids) . "'";
    $books = db_fetch_all("SELECT * FROM sach WHERE id_sach IN ($ids_str)");
    
    foreach ($books as $b) {
        $qty = $_SESSION['cart'][$b['id_sach']];
        $b['qty'] = $qty;
        $b['total'] = $b['gia'] * $qty; // Tổng tiền của sách đó
        $cart_items[] = $b;
        $total_money += $b['total'];
    }
}

$order_success = false;
$order_id = "";

// 2. XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT ĐẶT HÀNG (METHOD POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Lấy dữ liệu từ Form
    $hoten   = $_POST['hoten'] ?? '';
    $email   = $_POST['email'] ?? '';
    $sdt     = $_POST['sdt'] ?? '';
    $diachi  = $_POST['diachi'] ?? '';
    $tinh    = $_POST['tinh'] ?? '';
    
    // Ghép địa chỉ
    $full_diachi = $diachi;
    if (!empty($tinh)) $full_diachi .= ", " . $tinh;

    // Lấy ID người dùng nếu đã đăng nhập
    $id_nguoidung = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

    if ($total_money > 0) {
        // A. Tạo mã đơn hàng
        $order_id = generate_order_id();

        // B. Lưu đơn hàng vào bảng don_hang (Khớp với cấu trúc DB mới sửa)
        $sql_order = "INSERT INTO don_hang (id_donhang, id_nguoidung, hoten, email, sdt, diachi, tong_tien, ngay_dat, trang_thai, phuong_thuc_thanh_toan, trang_thai_thanh_toan) 
                      VALUES (:id, :idu, :hoten, :email, :sdt, :diachi, :tong, NOW(), 'Cho xu ly', 'COD', 'Chưa thanh toán')";
        
        db_run($sql_order, [
            ':id' => $order_id, 
            ':idu' => $id_nguoidung, 
            ':hoten' => $hoten, 
            ':email' => $email, 
            ':sdt' => $sdt, 
            ':diachi' => $full_diachi, 
            ':tong' => $total_money
        ]);

        // C. Lưu chi tiết đơn hàng
        foreach ($cart_items as $item) {
            $sql_detail = "INSERT INTO chi_tiet_don_hang (id_donhang, id_sach, so_luong, don_gia) 
                           VALUES (:dh, :sach, :sl, :gia)";
            db_run($sql_detail, [
                ':dh' => $order_id, 
                ':sach' => $item['id_sach'], 
                ':sl' => $item['qty'], 
                ':gia' => $item['gia']
            ]);
        }

        // D. Xóa giỏ hàng và báo thành công
        unset($_SESSION['cart']);
        $order_success = true;
    }
}
?>

<link rel="stylesheet" href="assets/css/checkout.css">

<div class="checkout-container">

    <?php if ($order_success): ?>
        <div class="success-box">
            <i class="fa-solid fa-circle-check success-icon"></i>
            <h1>Đặt Hàng Thành Công!</h1>
            <p>Mã đơn hàng của bạn: <span class="order-code"><?= $order_id ?></span></p>
            <p>Cảm ơn bạn <strong><?= htmlspecialchars($hoten) ?></strong> đã mua hàng tại Bookstore.</p>
            <a href="index.php" class="btn-home">Tiếp tục mua sắm</a>
        </div>

    <?php else: ?>
        <h1 class="page-title">Thanh toán đơn hàng</h1>
        
        <form action="" method="POST" class="checkout-grid">
            
            <div class="checkout-form">
                <h3>Thông tin giao hàng</h3>
                
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="hoten" placeholder="Ví dụ: Nguyễn Văn A" required>
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="email@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="sdt" placeholder="09xxxxxxxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tỉnh / Thành phố</label>
                    <select name="tinh">
                        <option value="">Chọn tỉnh thành</option>
                        <option value="Hà Nội">Hà Nội</option>
                        <option value="TP. HCM">TP. Hồ Chí Minh</option>
                        <option value="Đà Nẵng">Đà Nẵng</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Địa chỉ nhận hàng</label>
                    <input type="text" name="diachi" placeholder="Số nhà, tên đường, phường/xã..." required>
                </div>
            </div>

            <div class="checkout-summary">
                <h3>Đơn hàng (<?= count($cart_items) ?> sản phẩm)</h3>
                
                <div class="summary-list">
                    <?php foreach ($cart_items as $item): ?>
                    <div class="summary-item">
                        <div class="si-img">
                            <img src="../admin/uploads/<?= $item['hinh_anh'] ?>">
                            <span class="si-qty"><?= $item['qty'] ?></span>
                        </div>
                        <div class="si-info">
                            <div class="si-name"><?= $item['ten_sach'] ?></div>
                        </div>
                        <div class="si-price"><?= number_format($item['total']) ?>₫</div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-total">
                    <div class="row total">
                        <span>Tổng cộng</span>
                        <span><?= number_format($total_money) ?>₫</span>
                    </div>
                </div>

                <button type="submit" class="btn-confirm">Hoàn tất đơn hàng</button>
                <a href="index.php?page=cart" class="back-link">Quay lại giỏ hàng</a>
            </div>

        </form>
    <?php endif; ?>
</div>