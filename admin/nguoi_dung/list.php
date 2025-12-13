<?php
$pageTitle = "Danh sách người dùng";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

/* ===============================
   PHÂN TRANG + TÌM KIẾM
=============================== */
$limit   = 10;
$page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$keyword = trim($_GET['keyword'] ?? '');

$offset = ($page - 1) * $limit;

$where  = "nd.trang_thai = 1";
$params = [];

if ($keyword !== '') {
    $where .= " AND (
        nd.ho_ten LIKE :kw
        OR nd.email LIKE :kw
        OR nd.id_nguoidung LIKE :kw
    )";
    $params[':kw'] = "%$keyword%";
}

/* ===============================
   ĐẾM TỔNG USER
=============================== */
$totalRow = db_fetch("
    SELECT COUNT(*) AS total
    FROM nguoi_dung nd
    WHERE $where
", $params);

$total = $totalRow['total'] ?? 0;
$pages = max(1, ceil($total / $limit));

/* ===============================
   LẤY DANH SÁCH USER
=============================== */
$rows = db_fetch_all("
    SELECT 
        nd.id,
        nd.id_nguoidung,
        nd.ho_ten,
        nd.email,
        nd.so_dien_thoai,
        nd.trang_thai,
        vt.ten_vai_tro
    FROM nguoi_dung nd
    LEFT JOIN nguoi_dung_vai_tro ndvt 
        ON nd.id_nguoidung = ndvt.id_nguoidung
    LEFT JOIN vai_tro vt 
        ON ndvt.id_vaitro = vt.id_vaitro
    WHERE $where
    ORDER BY nd.id DESC
    LIMIT $limit OFFSET $offset
", $params);

if (!$rows) $rows = [];
?>

<link rel="stylesheet" href="../assets/css/nguoi_dung.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="page-header">
    <h1>Sách</h1>
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
    <td><?= htmlspecialchars($r['id_nguoidung']) ?></td>
    <td><?= htmlspecialchars($r['ho_ten']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>
    <td><?= htmlspecialchars($r['so_dien_thoai']) ?></td>
    <td><?= htmlspecialchars($r['ten_vai_tro']) ?></td>
    <td>
        <span class="badge <?= $r['trang_thai'] ? 'active' : 'inactive' ?>">
            <?= $r['trang_thai'] ? 'Hoạt động' : 'Khóa' ?>
        </span>
    </td>
    <td class="actions">
        <!-- EDIT: truyền ĐÚNG id_nguoidung -->
        <a class="edit"
           href="edit.php?id_nguoidung=<?= urlencode(trim($r['id_nguoidung'])) ?>">
             <i class="fa-solid fa-pen"></i>
        </a>

        <!-- DELETE (soft delete) -->
        <a class="delete"
           onclick="return confirm('Ẩn người dùng này?')"
           href="delete.php?id_nguoidung=<?= urlencode(trim($r['id_nguoidung'])) ?>">
            <i class="fa fa-trash"></i>
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<!-- PHÂN TRANG -->
<div class="pagination">
<?php for ($i = 1; $i <= $pages; $i++): ?>
    <a class="<?= ($i == $page) ? 'active' : '' ?>"
       href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>">
        <?= $i ?>
    </a>
<?php endfor; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
