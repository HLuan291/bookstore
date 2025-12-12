<?php
$pageTitle = "Sách";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

// Tìm kiếm
$keyword = $_GET['keyword'] ?? '';

// Phân trang
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$where = "";
$params = [];

if ($keyword) {
    $where = "WHERE s.ten_sach LIKE :kw OR s.id_sach LIKE :kw";
    $params[':kw'] = "%$keyword%";
}

// Tổng số sách
$total = db_fetch("SELECT COUNT(*) AS total FROM sach s $where", $params)['total'];
$pages = ceil($total / $limit);

// Lấy dữ liệu đúng theo DB
$rows = db_fetch_all("
    SELECT 
        s.id,
        s.id_sach,
        s.ten_sach,
        s.tac_gia,
        s.gia,
        s.ton_kho,
        s.hinh_anh,
        d.ten_danh_muc
    FROM sach s
    LEFT JOIN danh_muc d ON s.id_danhmuc = d.id_danhmuc
    $where
    ORDER BY s.id asc
    LIMIT $offset, $limit
", $params);
?>

<link rel="stylesheet" href="../assets/css/sach.css">

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
    <th>Mã sách</th>
    <th>Tên sách</th>
    <th>Tác giả</th>
    <th>Danh mục</th>
    <th>Giá</th>
    <th>Tồn kho</th>
    <th>Ảnh</th>
    <th>Hành động</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td>#<?= $r['id_sach'] ?></td>
    <td><?= $r['ten_sach'] ?></td>
    <td><?= $r['tac_gia'] ?></td>
    <td><?= $r['ten_danh_muc'] ?></td>
    <td><?= number_format($r['gia']) ?>đ</td>
    <td><?= $r['ton_kho'] ?></td>

    <td>
        <?php if ($r['hinh_anh']): ?>
            <img src="../uploads/<?= $r['hinh_anh'] ?>" class="thumb">
        <?php else: ?>
            <span class="no-img">Không ảnh</span>
        <?php endif; ?>
    </td>

    <td class="actions">
        <a class="edit" href="edit.php?id=<?= $r['id'] ?>"><i class="fa-solid fa-pen"></i></a>
        <a class="delete" onclick="return confirm('Xóa sách này?')" href="delete.php?id=<?= $r['id'] ?>">
            <i class="fa-solid fa-trash"></i>
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<!-- PHÂN TRANG -->
<div class="pagination">
<?php for ($i = 1; $i <= $pages; $i++): ?>
    <a class="<?= ($i == $page) ? 'active' : '' ?>"
       href="?page=<?= $i ?>&keyword=<?= htmlspecialchars($keyword) ?>">
        <?= $i ?>
    </a>
<?php endfor; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
