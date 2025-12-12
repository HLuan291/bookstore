<?php
// Bật session và lấy các hàm cần thiết
session_start();
// Đảm bảo functions.php chứa hàm db_fetch và db_query
require_once __DIR__ . '/../includes/functions.php'; 

// Thiết lập header để trả về JSON
header('Content-Type: application/json');

// --- 1. LẤY DỮ LIỆU 4 BOX THỐNG KÊ ---

// 1.1. Tổng đơn hàng (Tất cả)
$res_orders = db_fetch("SELECT COUNT(*) as totalOrders FROM don_hang");
$totalOrders = $res_orders['totalOrders'] ?? 0;

// 1.2. Tổng doanh thu (Chỉ đơn hàng 'Hoàn thành')
$res_revenue = db_fetch("SELECT SUM(tong_tien) as totalRevenue FROM don_hang WHERE trang_thai = 'Hoàn thành'");
// Số phải là kiểu integer/float để JS .toLocaleString() hoạt động
$totalRevenue = (float)($res_revenue['totalRevenue'] ?? 0); 

// 1.3. Người dùng mới (Khách hàng role VT03, tạo trong 30 ngày gần nhất)
$res_new_users = db_fetch("
    SELECT COUNT(nd.id_nguoidung) as newUsers 
    FROM nguoi_dung nd
    JOIN nguoi_dung_vai_tro ndvt ON nd.id_nguoidung = ndvt.id_nguoidung
    WHERE ndvt.id_vaitro = 'VT03' AND nd.ngay_tao >= DATE_SUB(NOW(), INTERVAL 30 DAY)
");
$newUsers = $res_new_users['newUsers'] ?? 0;

// 1.4. Đơn đang giao (Status = 'Đang giao')
$res_shipping = db_fetch("SELECT COUNT(*) as shippingOrders FROM don_hang WHERE trang_thai = 'Đang giao'");
$shippingOrders = $res_shipping['shippingOrders'] ?? 0;


// --- 2. DỮ LIỆU BIỂU ĐỒ DOANH THU (12 tháng gần nhất) ---

$revenueLabels = [];
$revenueValues = [];
$currentMonth = date('Y-m');

for ($i = 11; $i >= 0; $i--) {
    // Tính toán tháng lùi
    $month = date('Y-m', strtotime("-$i month"));
    $displayMonth = date('m/Y', strtotime($month));
    
    // Query tổng doanh thu của tháng đó
    $sql_month_rev = "
        SELECT SUM(tong_tien) as monthlyTotal 
        FROM don_hang 
        WHERE DATE_FORMAT(ngay_dat, '%Y-%m') = :month AND trang_thai = 'Hoàn thành'
    ";
    
    $res = db_fetch($sql_month_rev, [':month' => $month]);
    
    $revenueLabels[] = $displayMonth;
    $revenueValues[] = (float)($res['monthlyTotal'] ?? 0);
}


// --- 3. TOP SẢN PHẨM BÁN CHẠY (Top 5) ---

$topProducts = db_fetch_all("
    SELECT 
        s.ten_sach,
        SUM(ct.so_luong) as so_luong_ban
    FROM 
        chi_tiet_don_hang ct
    JOIN 
        sach s ON ct.id_sach = s.id_sach
    GROUP BY 
        s.ten_sach
    ORDER BY 
        so_luong_ban DESC
    LIMIT 5
");


// --- 4. TỔNG HỢP VÀ TRẢ VỀ JSON ---

$output = [
    // 4 Box Stats
    'totalOrders'    => $totalOrders,
    'totalRevenue'   => $totalRevenue,
    'newUsers'       => $newUsers,
    'shippingOrders' => $shippingOrders,
    
    // Biểu đồ
    'revenue'        => [
        'labels' => $revenueLabels,
        'values' => $revenueValues
    ],
    
    // Top Sản phẩm
    'topProducts'    => $topProducts
];

echo json_encode($output);
?>