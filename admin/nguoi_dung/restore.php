<?php
require_once __DIR__ . '/../includes/functions.php';

$id_nguoidung = trim($_GET['id_nguoidung'] ?? '');

if ($id_nguoidung === '') {
    header("Location: list_hidden.php");
    exit;
}

/* Kiểm tra user */
$user = db_fetch("
    SELECT id_nguoidung, trang_thai
    FROM nguoi_dung
    WHERE id_nguoidung = :id
", [
    ':id' => $id_nguoidung
]);

if (!$user) {
    header("Location: list_hidden.php?msg=notfound");
    exit;
}

/* Chỉ khôi phục user bị ẩn */
if ((int)$user['trang_thai'] === 1) {
    header("Location: list_hidden.php?msg=already_active");
    exit;
}

/* KHÔI PHỤC */
db_execute("
    UPDATE nguoi_dung
    SET trang_thai = 1
    WHERE id_nguoidung = :id
", [
    ':id' => $id_nguoidung
]);

header("Location: list_hidden.php?msg=restored");
exit;
