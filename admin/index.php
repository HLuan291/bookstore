<?php
$pageTitle = "Thống kê Doanh thu";
require_once __DIR__ . '/includes/header.php';

/* =============================
   TỔNG THỐNG KÊ
============================= */

// Tổng doanh thu (đơn đã giao)
$row = db_fetch("
    SELECT SUM(tong_tien) AS tong
    FROM don_hang
    WHERE trang_thai = 'Da giao'
");
$tong_tien = (int)($row['tong'] ?? 0);

// Tổng đơn hàng
$row = db_fetch("SELECT COUNT(*) AS tong FROM don_hang");
$tong_don = (int)($row['tong'] ?? 0);

// Tổng sách đã bán
$row = db_fetch("SELECT SUM(so_luong) AS tong FROM chi_tiet_don_hang");
$sach_ban = (int)($row['tong'] ?? 0);

// Khách hàng mới trong tháng
$row = db_fetch("
    SELECT COUNT(*) AS tong
    FROM nguoi_dung
    WHERE MONTH(ngay_tao) = MONTH(CURDATE())
      AND YEAR(ngay_tao) = YEAR(CURDATE())
");
$khach_moi = (int)($row['tong'] ?? 0);

/* =============================
   CHART THEO NGÀY (7 NGÀY)
============================= */
$raw_day = db_fetch_all("
    SELECT DATE(ngay_dat) AS ngay, SUM(tong_tien) AS doanh_thu
    FROM don_hang
    WHERE trang_thai = 'Da giao'
      AND ngay_dat >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(ngay_dat)
");

$labels_day = [];
$data_day   = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels_day[] = date('d/m', strtotime($date));

    $value = 0;
    foreach ($raw_day as $r) {
        if ($r['ngay'] === $date) {
            $value = (int)$r['doanh_thu'];
            break;
        }
    }
    $data_day[] = $value;
}

/* =============================
   CHART THEO THÁNG (12 THÁNG)
============================= */
$raw_month = db_fetch_all("
    SELECT MONTH(ngay_dat) AS thang, SUM(tong_tien) AS doanh_thu
    FROM don_hang
    WHERE trang_thai = 'Da giao'
      AND YEAR(ngay_dat) = YEAR(CURDATE())
    GROUP BY MONTH(ngay_dat)
");

$labels_month = [];
$data_month   = [];

for ($m = 1; $m <= 12; $m++) {
    $labels_month[] = "Th $m";

    $value = 0;
    foreach ($raw_month as $r) {
        if ((int)$r['thang'] === $m) {
            $value = (int)$r['doanh_thu'];
            break;
        }
    }
    $data_month[] = $value;
}

/* =============================
   CHART THEO NĂM
============================= */
$raw_year = db_fetch_all("
    SELECT YEAR(ngay_dat) AS nam, SUM(tong_tien) AS doanh_thu
    FROM don_hang
    WHERE trang_thai = 'Da giao'
    GROUP BY YEAR(ngay_dat)
    ORDER BY nam
");

$labels_year = [];
$data_year   = [];

foreach ($raw_year as $r) {
    $labels_year[] = $r['nam'];
    $data_year[]   = (int)$r['doanh_thu'];
}
?>

<link rel="stylesheet" href="assets/css/thong_ke.css">

<div class="main-content">

    <h2 class="page-title">Thống kê Doanh thu</h2>

    <!-- STAT CARDS -->
    <div class="stat-wrapper">
        <div class="stat-box">
            <div class="icon orange"><i class="fa-solid fa-dollar-sign"></i></div>
            <div>
                <p>Tổng Doanh Thu</p>
                <h3><?= number_format($tong_tien) ?> đ</h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="icon green"><i class="fa-solid fa-bag-shopping"></i></div>
            <div>
                <p>Tổng Đơn Hàng</p>
                <h3><?= $tong_don ?></h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="icon blue"><i class="fa-solid fa-book"></i></div>
            <div>
                <p>Sách Đã Bán</p>
                <h3><?= $sach_ban ?></h3>
            </div>
        </div>

        <div class="stat-box">
            <div class="icon purple"><i class="fa-solid fa-users"></i></div>
            <div>
                <p>Khách Hàng Mới</p>
                <h3><?= $khach_moi ?></h3>
            </div>
        </div>
    </div>

    <!-- CHART -->
    <div class="chart-container">
        <div class="chart-header">
            <h3>Xu Hướng Doanh Thu</h3>
            <div class="chart-tabs">
                <button class="active" data-type="day">Ngày</button>
                <button data-type="month">Tháng</button>
                <button data-type="year">Năm</button>
            </div>
        </div>

        <canvas id="revenueChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const chartData = {
    day: {
        labels: <?= json_encode($labels_day) ?>,
        data: <?= json_encode($data_day) ?>
    },
    month: {
        labels: <?= json_encode($labels_month) ?>,
        data: <?= json_encode($data_month) ?>
    },
    year: {
        labels: <?= json_encode($labels_year) ?>,
        data: <?= json_encode($data_year) ?>
    }
};

const ctx = document.getElementById('revenueChart');
let chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.day.labels,
        datasets: [{
            data: chartData.day.data,
            borderColor: '#FF7A00',
            backgroundColor: 'rgba(255,122,0,0.15)',
            fill: true,
            tension: 0.35,
            pointRadius: 4
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

document.querySelectorAll('.chart-tabs button').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.chart-tabs button')
            .forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const type = btn.dataset.type;
        chart.data.labels = chartData[type].labels;
        chart.data.datasets[0].data = chartData[type].data;
        chart.update();
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
