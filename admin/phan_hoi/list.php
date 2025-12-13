<?php
$pageTitle = "Phản hồi";
require_once __DIR__ . '/../includes/header.php';

$keyword = $_GET["keyword"] ?? "";

// Phân trang
$limit = 10;
$page = max(1, intval($_GET["page"] ?? 1));
$offset = ($page - 1) * $limit;

$where = "";
$params = [];

if ($keyword !== "") {  
    $where = "WHERE ph.noi_dung LIKE :kw OR nd.ho_ten LIKE :kw OR ph.id_phanhoi LIKE :kw";
    $params[':kw'] = "%$keyword%";
}

// Tổng dòng
$total = db_fetch("
    SELECT COUNT(*) AS total 
    FROM phan_hoi ph
    LEFT JOIN nguoi_dung nd ON ph.id_nguoidung = nd.id_nguoidung
    $where
", $params)['total'];

$pages = ceil($total / $limit);

// Lấy danh sách phản hồi
$rows = db_fetch_all("
    SELECT ph.*, nd.ho_ten, nd.email
    FROM phan_hoi ph
    LEFT JOIN nguoi_dung nd ON ph.id_nguoidung = nd.id_nguoidung
    $where
    ORDER BY ph.ngay_tao DESC   -- sửa ngày_gui → ngày_tao
    LIMIT $offset, $limit
", $params);

?>
<link rel="stylesheet" href="../assets/css/phan_hoi.css">
<div class="page-header">
    <h1>Phản hồi người dùng</h1>
</div>

<div class="search-box">
    <form>
        <input name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tìm kiếm phản hồi...">
    </form>
</div>

<table class="table-admin">
<tr>
    <th>ID</th>
    <th>Mã PH</th>
    <th>Người gửi</th>
    <th>Email</th>
    <th>Nội dung</th>
    <th>Ngày gửi</th>
    <th>Hành động</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r["id"] ?></td>
    <td><?= $r["id_phanhoi"] ?></td>
    <td><?= $r["ho_ten"] ?></td>
    <td><?= $r["email"] ?></td>

    <!-- Rút gọn nội dung nếu dài -->
    <td><?= strlen($r["noi_dung"]) > 40 ? substr($r["noi_dung"], 0, 40) . "..." : $r["noi_dung"] ?></td>

    <!-- NGÀY GỬI: đúng cột bảng DATABASE -->
    <td><?= $r["ngay_tao"] ?></td>

    <td class="actions">
        <a class="view" href="detail.php?id=<?= $r['id'] ?>">
            <i class="fa-solid fa-eye"></i>
        </a>

        <a class="delete" onclick="return confirm('Xóa phản hồi này?')" 
           href="handle.php?action=delete&id=<?= $r['id'] ?>">
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
       href="?page=<?= $i ?>&keyword=<?= $keyword ?>">
       <?= $i ?>
    </a>
<?php endfor; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
