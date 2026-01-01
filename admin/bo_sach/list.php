<?php
$pageTitle = "Bộ sách";
require_once __DIR__ . '/../includes/header.php';

// Tìm kiếm
$keyword = $_GET['keyword'] ?? "";

// Phân trang
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$where = "";
$params = [];

if ($keyword !== "") {
    $where = "WHERE ten_bo_sach LIKE :kw OR id_bosach LIKE :kw";
    $params[':kw'] = "%$keyword%";
}

// Tổng số dòng
$total = db_fetch("SELECT COUNT(*) AS total FROM bo_sach $where", $params)['total'];
$pages = ceil($total / $limit);

// Lấy dữ liệu
$rows = db_fetch_all("
    SELECT b.*, s.ten_sach, d.ten_danh_muc
    FROM bo_sach b
    LEFT JOIN sach s ON b.id_sach = s.id_sach
    LEFT JOIN danh_muc d ON b.id_danhmuc = d.id_danhmuc
    $where
    ORDER BY b.id DESC
    LIMIT $offset, $limit
", $params);
?>
<link rel="stylesheet" href="../assets/css/bo_sach.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="page-header">
    <h1>Bộ Sách</h1>
    <a href="add.php" class="btn-add">+ Thêm</a>
</div>

<div class="search-box">
    <form>
        <input name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tìm kiếm sách...">
    </form>
</div>

<table class="table-admin">
<tr>
    <th>ID</th>
    <th>Mã bộ sách</th>
    <th>Tên bộ sách</th>
    <th>Sách</th>
    <th>Danh mục</th>
    <th>Giá</th>
    <th>Hành động</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['id_bosach'] ?></td>
    <td><?= $r['ten_bo_sach'] ?></td>
    <td><?= $r['ten_sach'] ?></td>
    <td><?= $r['ten_danh_muc'] ?></td>
    <td><?= number_format($r['gia']) ?>đ</td>

    <td class="actions">
        <a class="edit" href="edit.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen"></i></a>
        <a class="delete" onclick="return confirm('Xóa bộ sách này?')" href="delete.php?id=<?= $r['id'] ?>">
            <i class="fa-solid fa-trash"></i>
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<!-- PHÂN TRANG -->
<div class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a class="<?= ($i == $page) ? 'active' : '' ?>" href="?page=<?= $i ?>&keyword=<?= $keyword ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
