<?php
require_once __DIR__ . '/../includes/functions.php';

/*
  Hỗ trợ 2 kiểu:
  - ?id_nguoidung=U041111  (CHAR)
  - ?id=34                (INT)
*/

$id_nguoidung = trim($_GET['id_nguoidung'] ?? '');
$id_int       = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_nguoidung === '' && $id_int === 0) {
    header("Location: list.php");
    exit;
}

/* ===============================
   LẤY USER THEO ĐÚNG KIỂU ID
================================ */
if ($id_nguoidung !== '') {
    // Ưu tiên id_nguoidung (CHAR)
    $user = db_fetch("
        SELECT id_nguoidung, trang_thai
        FROM nguoi_dung
        WHERE id_nguoidung = :id
    ", [
        ':id' => $id_nguoidung
    ]);
} else {
    // Fallback theo id INT
    $user = db_fetch("
        SELECT id_nguoidung, trang_thai
        FROM nguoi_dung
        WHERE id = :id
    ", [
        ':id' => $id_int
    ]);
}

if (!$user) {
    header("Location: list.php?msg=notfound");
    exit;
}

if ((int)$user['trang_thai'] === 0) {
    header("Location: list.php?msg=already_disabled");
    exit;
}

/* ===============================
   SOFT DELETE (KHÓA USER)
================================ */
db_execute("
    UPDATE nguoi_dung
    SET trang_thai = 0
    WHERE id_nguoidung = :id
", [
    ':id' => $user['id_nguoidung'] // luôn update theo mã chuẩn
]);

header("Location: list.php?msg=disabled");
exit;
