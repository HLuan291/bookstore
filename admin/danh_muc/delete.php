<?php
require_once __DIR__ . '/../includes/functions.php';

// Lấy ID từ URL
$id = intval($_GET['id'] ?? 0);

// Nếu ID không hợp lệ
if ($id <= 0) {
    die("ID không hợp lệ!");
}

// Kiểm tra danh mục có tồn tại không
$dm = db_fetch("SELECT * FROM danh_muc WHERE id = :id", [':id' => $id]);

if (!$dm) {
    die("Danh mục không tồn tại!");
}

// Xóa danh mục
db_execute("DELETE FROM danh_muc WHERE id = :id", [':id' => $id]);

// Quay về danh sách
header("Location: list.php?msg=deleted");
exit;
?>
