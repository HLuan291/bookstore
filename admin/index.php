<?php
$pageTitle = "Thống kê Doanh thu";
require_once __DIR__ . '/includes/header.php';

// --- LẤY DỮ LIỆU THỐNG KÊ ---

// Tổng doanh thu hoàn thành
$tien = db_fetch("SELECT SUM(tong_tien) as tong FROM don_hang WHERE trang_thai = 'Hoàn thành'");
$tong_tien = $tien['tong'] ?? 0;

// Tổng đơn hàng
$don = db_fetch("SELECT COUNT(*) AS tong FROM don_hang");
$tong_don = $don['tong'] ?? 0;

// Tổng số sách bán (ví dụ dùng trường so_luong trong chi tiết đơn hàng)
$sach = db_fetch("SELECT SUM(so_luong) AS tong FROM chi_tiet_don_hang");
$sach_ban = $sach['tong'] ?? 0;

// Tổng khách hàng mới trong tháng
$kh = db_fetch("SELECT COUNT(*) AS tong FROM nguoi_dung WHERE MONTH(ngay_tao) = MONTH(NOW())");
$khach_moi = $kh['tong'] ?? 0;

?>

<link rel="stylesheet" href="assets/css/thong_ke.css">

<div class="main-content">

    <h2 class="page-title">Thống kê Doanh thu</h2>

    <div class="stat-wrapper">

        <div class="stat-box">
            <div class="icon orange"><i class="fa-solid fa-dollar-sign"></i></div>
            <div class="info">
                <p>Tổng Doanh Thu</p>
                <h3><?= number_format($tong_tien) ?> đ</h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="icon green"><i class="fa-solid fa-bag-shopping"></i></div>
            <div class="info">
                <p>Tổng Đơn Hàng</p>
                <h3><?= $tong_don ?></h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="icon blue"><i class="fa-solid fa-book"></i></div>
            <div class="info">
                <p>Sách Đã Bán</p>
                <h3><?= $sach_ban ?></h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="icon purple"><i class="fa-solid fa-users"></i></div>
            <div class="info">
                <p>Khách Hàng Mới</p>
                <h3><?= $khach_moi ?></h3>
            </div>
        </div>

    </div>

    <!-- BIỂU ĐỒ -->
    <div class="chart-container">
        <div class="chart-header">
            <h3>Xu Hướng Doanh Thu</h3>

            <div class="chart-tabs">
                <button class="active">Ngày</button>
                <button>Tháng</button>
                <button>Năm</button>
            </div>
        </div>

        <canvas id="revenueChart"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('revenueChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        datasets: [{
            data: [1.2, 1.8, 1.5, 2.5, 2.2, 3.0, 2.8],
            borderColor: "#FF7A00",
            backgroundColor: "rgba(255,122,0,0.15)",
            fill: true,
            tension: 0.35,
            pointBackgroundColor: "#FF7A00",
            pointRadius: 5
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: false, min: 0, max: 3.2 }
        }
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
