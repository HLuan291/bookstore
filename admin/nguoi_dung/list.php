<?php
$pageTitle = "Người dùng";
$current_page = "nguoi_dung";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
// Tìm kiếm
$keyword = $_GET['keyword'] ?? '';

// Phân trang
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$where = "";
$params = [];

if ($keyword !== "") {
    $where = "WHERE ho_ten LIKE :kw OR email LIKE :kw OR id_nguoidung LIKE :kw";
    $params[':kw'] = "%$keyword%";
}

// Tổng
$total = db_fetch("SELECT COUNT(*) AS total FROM nguoi_dung $where", $params)['total'];
$pages = ceil($total / $limit);

// Lấy dữ liệu
$rows = db_fetch_all("
    SELECT nd.*, vt.ten_vai_tro
    FROM nguoi_dung nd
    LEFT JOIN nguoi_dung_vai_tro nvt 
        ON nd.id_nguoidung = nvt.id_nguoidung
    LEFT JOIN vai_tro vt 
        ON nvt.id_vaitro = vt.id_vaitro
    $where
    ORDER BY nd.id ASC
    LIMIT $offset, $limit
", $params);

?>
<link rel="stylesheet" href="../assets/css/nguoi_dung.css">
<div class="page-header">
    <h1>Người dùng</h1>
    <a href="add.php" class="btn-add">+ Thêm</a>
</div>

<table class="table-admin">
<tr>
    <th>ID</th>
    <th>Mã ND</th>
    <th>Họ tên</th>
    <th>Email</th>
    <th>SĐT</th>
    <th>Vai trò</th>
    <th>Trạng thái</th>
    <th>Hành động</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['id_nguoidung'] ?></td>
    <td><?= $r['ho_ten'] ?></td>
    <td><?= $r['email'] ?></td>
    <td><?= $r['so_dien_thoai'] ?></td>
    <td><?= $r['ten_vai_tro'] ?></td>

    <td>
        <?php if ($r['trang_thai']): ?>
            <span class="badge active">Hoạt động</span>
        <?php else: ?>
            <span class="badge inactive">Khóa</span>
        <?php endif; ?>
    </td>

    <td class="actions">
        <a class="edit" href="edit.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen"></i></a>
        <a class="delete" onclick="return confirm('Xóa người dùng này?')" href="delete.php?id=<?= $r['id'] ?>">
            <i class="fa-solid fa-trash"></i>
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<!-- Phân trang -->
<div class="pagination">
<?php for ($i = 1; $i <= $pages; $i++): ?>
    <a class="<?= ($i == $page) ? 'active' : '' ?>" href="?page=<?= $i ?>&keyword=<?= $keyword ?>"><?= $i ?></a>
<?php endfor; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
