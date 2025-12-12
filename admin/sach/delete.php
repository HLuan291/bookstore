<?php
require_once __DIR__ . '/../includes/functions.php';

// Lấy ID sách từ URL
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID sách không hợp lệ");
}

// Lấy thông tin sách để xóa ảnh
$sach = db_fetch("SELECT hinh_anh FROM sach WHERE id = :id", [':id' => $id]);

if (!$sach) {
    die("Không tìm thấy sách để xóa");
}

// XÓA FILE ẢNH nếu tồn tại
if (!empty($sach['hinh_anh'])) {
    $filePath = __DIR__ . "/../../uploads/" . $sach['hinh_anh'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// XÓA SÁCH TRONG DATABASE
db_execute("DELETE FROM sach WHERE id = :id", [':id' => $id]);

// Quay về danh sách
header("Location: list.php?deleted=1");
exit;
?>
