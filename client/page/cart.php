<?php
// ------------------------------------------------------------------
// PHẦN 1: XỬ LÝ LOGIC (PHP)
// ------------------------------------------------------------------

error_reporting(E_ALL & ~E_NOTICE);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Khởi tạo giỏ hàng
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. Xử lý hành động: THÊM
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    // SỬA LỖI: Lấy ID dạng chuỗi (String) thay vì số (Int)
    $id = isset($_GET['id']) ? trim($_GET['id']) : '';
    
    // Chỉ xử lý nếu ID không rỗng
    if ($id !== '') {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
    }
    
    // Quay lại trang trước đó
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    if (strpos($redirect_url, 'action=') !== false) {
        $redirect_url = 'index.php'; 
    }
    echo "<script>window.location.href='$redirect_url';</script>";
    exit();
}

// 2. Xử lý hành động: GIẢM
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $id = isset($_GET['id']) ? trim($_GET['id']) : '';
    
    if ($id !== '' && isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--; 
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
    echo "<script>window.location.href='index.php?page=cart';</script>";
    exit();
}

// 3. Lấy dữ liệu sách từ Database
$cart_list = [];
$total_money = 0;

if (!empty($_SESSION['cart'])) {
    $ids_array = array_keys($_SESSION['cart']);
    
    if (!empty($ids_array)) {
        // SỬA LỖI QUERY: Vì id_sach là chuỗi, cần bao quanh bằng dấu nháy đơn
        // Ví dụ: 'S001','S002'
        $ids_string = "'" . implode("','", $ids_array) . "'";
        
        // Truy vấn theo cột 'id_sach' (char 10) thay vì 'id'
        $sql = "SELECT * FROM sach WHERE id_sach IN ($ids_string)";
        
        // Gọi hàm từ functions.php
        if (function_exists('db_fetch_all')) {
            $result = db_fetch_all($sql);
            
            if ($result) {
                foreach ($result as $book) {
                    // Kiểm tra key trong session
                    $book_id = $book['id_sach']; // Lấy đúng cột id_sach
                    if(isset($_SESSION['cart'][$book_id])) {
                        $book['qty'] = $_SESSION['cart'][$book_id];
                        $cart_list[] = $book;
                    }
                }
            }
        }
    }
}
?>

<link rel="stylesheet" href="assets/css/cart.css">

<div class="cart-container">
    <h1 class="cart-title">Giỏ hàng của bạn</h1>
    <div class="cart-grid">
        <div class="cart-items">
            <?php if (empty($cart_list)): ?>
                <div style="text-align: center; padding: 40px; background: #fff; border-radius: 8px;">
                    <p style="font-size: 18px; color: #555;">Giỏ hàng trống.</p>
                    <a href="index.php" class="continue-link" style="display:inline-block; margin-top:15px;">← Quay lại mua sắm</a>
                </div>
            <?php else: ?>
                <?php foreach ($cart_list as $item): 
                    $thanh_tien = $item['gia'] * $item['qty'];
                    $total_money += $thanh_tien;
                ?>
                <div class="cart-item">
                    <img src="../admin/uploads/<?= $item['hinh_anh'] ?>" class="cart-img" alt="<?= $item['ten_sach'] ?>">
                    <div class="cart-info">
                        <div class="cart-name"><?= $item['ten_sach'] ?></div>
                        <div class="cart-author"><?= $item['tac_gia'] ?></div>
                    </div>
                    <div class="cart-qty">
                        <a href="index.php?page=cart&action=remove&id=<?= $item['id_sach'] ?>" class="qty-btn" style="text-decoration: none;">−</a>
                        <span class="qty-number"><?= $item['qty'] ?></span>
                        <a href="index.php?page=cart&action=add&id=<?= $item['id_sach'] ?>" class="qty-btn" style="text-decoration: none;">+</a>
                    </div>
                    <div class="cart-price"><?= number_format($item['gia']) ?>₫</div>
                    <div class="cart-total"><strong><?= number_format($thanh_tien) ?>₫</strong></div>
                </div>
                <?php endforeach; ?>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <a href="index.php" class="continue-link">← Tiếp tục mua sắm</a>
                    <div style="font-size: 18px;">
                        Tổng tiền: <strong style="color: #b91c1c; font-size: 22px;"><?= number_format($total_money) ?>₫</strong>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="cart-shipping">
            <h3 class="ship-title">Thanh toán</h3>
            <form class="ship-form" action="index.php?page=checkout" method="POST">
                <input type="text" name="hoten" placeholder="Họ tên" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="sdt" placeholder="Số điện thoại" required>
                <input type="text" name="diachi" placeholder="Địa chỉ" required>
                
                <?php if (!empty($cart_list)): ?>
                    <button type="submit" class="btn-checkout" style="width:100%; border:none; cursor:pointer; margin-top:20px;">Đặt hàng</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>