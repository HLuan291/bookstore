<?php
$pageTitle = "Đơn hàng";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';

$keyword = $_GET["keyword"] ?? "";

$limit = 10;
$page = max(1, intval($_GET["page"] ?? 1));
$offset = ($page - 1) * $limit;

$where = "";
$params = [];

if ($keyword !== "") {
    $where = "WHERE dh.id_donhang LIKE :kw OR nd.ho_ten LIKE :kw OR dh.trang_thai LIKE :kw";
    $params[':kw'] = "%$keyword%";
}

$total = db_fetch("
    SELECT COUNT(*) AS total
    FROM don_hang dh
    LEFT JOIN nguoi_dung nd ON dh.id_nguoidung = nd.id_nguoidung
    $where
", $params)['total'];

$pages = ceil($total / $limit);

$rows = db_fetch_all("
    SELECT dh.*, nd.ho_ten, nd.email
    FROM don_hang dh
    LEFT JOIN nguoi_dung nd ON dh.id_nguoidung = nd.id_nguoidung
    $where
    ORDER BY dh.ngay_dat DESC
    LIMIT $offset, $limit
", $params);
?>
<link rel="stylesheet" href="../assets/css/don_hang.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="page-header">
    <h1>Đơn hàng</h1>
</div>

<div class="search-box">
    <form>
        <input name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tìm kiếm đơn hàng...">
    </form>
</div>

<table class="table-admin">
<tr>
    <th>ID</th>
    <th>Mã ĐH</th>
    <th>Khách hàng</th>
    <th>Email</th>
    <th>Ngày đặt</th>
    <th>Trạng thái</th>
    <th>Tổng tiền</th>
    <th>Hành động</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['id_donhang'] ?></td>
    <td><?= $r['ho_ten'] ?></td>
    <td><?= $r['email'] ?></td>
    <td><?= $r['ngay_dat'] ?></td>
    <td>
        <?php if ($r['trang_thai']=="Cho xu ly"): ?>
            <span class="badge pending">Chờ xử lý</span>
        <?php elseif ($r['trang_thai']=="Dang giao"): ?>
            <span class="badge shipping">Đang giao</span>
        <?php elseif ($r['trang_thai']=="Da giao"): ?>
            <span class="badge success">Đã giao</span>
        <?php else: ?>
            <span class="badge cancel">Đã hủy</span>
        <?php endif; ?>
    </td>
    <td><?= number_format($r['tong_tien'], 0, ',', '.') ?>đ</td>

    <td class="actions">
        <a class="view" href="detail.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-eye"></i></a>
        <a class="edit" href="update.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen"></i></a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<!-- PAGINATION -->
<div class="pagination">
<?php for ($i = 1; $i <= $pages; $i++): ?>
    <a class="<?= ($i==$page?'active':'') ?>" href="?page=<?= $i ?>&keyword=<?= $keyword ?>"><?= $i ?></a>
<?php endfor; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
