<?php
$pageTitle = "Chi tiết phản hồi";
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET["id"] ?? 0);

$data = db_fetch("
    SELECT ph.*, nd.ho_ten, nd.email, nd.sdt
    FROM phan_hoi ph
    LEFT JOIN nguoi_dung nd ON ph.id_nguoidung = nd.id_nguoidung
    WHERE ph.id = :id
", [":id" => $id]);

if (!$data) die("Không tìm thấy phản hồi");
?>

<div class="form-page">
<h1>Chi tiết phản hồi</h1>

<div class="info-box">
    <p><strong>Mã phản hồi:</strong> <?= $data["id_phanhoi"] ?></p>
    <p><strong>Người gửi:</strong> <?= $data["ho_ten"] ?></p>
    <p><strong>Email:</strong> <?= $data["email"] ?></p>
    <p><strong>SĐT:</strong> <?= $data["sdt"] ?></p>
    <p><strong>Ngày gửi:</strong> <?= $data["ngay_gui"] ?></p>
</div>

<div class="message-box">
    <h3>Nội dung phản hồi:</h3>
    <div class="content"><?= nl2br($data["noi_dung"]) ?></div>
</div>

<a href="handle.php?action=delete&id=<?= $id ?>" 
   onclick="return confirm('Bạn chắc chắn muốn xóa phản hồi này?')"
   class="btn-delete">Xóa phản hồi</a>

<a href="list.php" class="btn-back">Quay lại</a>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
