<?php
require_once __DIR__ . '/../includes/header.php';
$current_page = "danhmuc";
$limit = 10;
$page  = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$start = ($page - 1) * $limit;

$keyword = trim($_GET['keyword'] ?? "");

// Tổng số danh mục
$sqlCount = "
    SELECT COUNT(*) AS total
    FROM danh_muc
    WHERE ten_danh_muc LIKE :kw
";
$total = db_fetch($sqlCount, [':kw' => "%$keyword%"])['total'];
$totalPages = ceil($total / $limit);

// Lấy danh sách danh mục
$sql = "
    SELECT *
    FROM danh_muc
    WHERE ten_danh_muc LIKE :kw
    ORDER BY id_danhmuc ASC
    LIMIT $start, $limit
";
$rows = db_fetch_all($sql, [':kw' => "%$keyword%"]);
?>

<link rel="stylesheet" href="../assets/css/danh_muc.css">
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
        <th>Tên danh mục</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($rows as $r): ?>
    <tr>
        <td><?= $r['id_danhmuc'] ?></td>

        <td><?= htmlspecialchars($r['ten_danh_muc']) ?></td>

        <td>
            <span class="badge <?= $r['trang_thai'] ? 'active' : 'inactive' ?>">
                <?= $r['trang_thai'] ? 'Hoạt động' : 'Ẩn' ?>
            </span>
        </td>

        <td class="actions">

            <a class="icon edit" href="edit.php?id=<?= $r['id'] ?>">
               <i class="fa-solid fa-pen"></i>
            </a>

            <a class="icon delete"
               onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')"
               href="delete.php?id=<?= $r['id'] ?>">
                <i class="fa fa-trash"></i>
            </a>

        </td>
    </tr>
    <?php endforeach; ?>

</table>

<!-- PAGINATION -->
<div class="pagination">

    <?php if ($page > 1): ?>
        <a href="?page=danh_muc&p=1&keyword=<?= $keyword ?>">«</a>
        <a href="?page=danh_muc&p=<?= $page - 1 ?>&keyword=<?= $keyword ?>">‹</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="<?= ($i == $page) ? 'active' : '' ?>"
           href="?page=danh_muc&p=<?= $i ?>&keyword=<?= $keyword ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=danh_muc&p=<?= $page + 1 ?>&keyword=<?= $keyword ?>">›</a>
        <a href="?page=danh_muc&p=<?= $totalPages ?>&keyword=<?= $keyword ?>">»</a>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
