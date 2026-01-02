<?php

// 1. Lấy thông tin sách cần đánh giá dựa trên id_sach
$id_sach = isset($_GET['id']) ? trim($_GET['id']) : '';
$book = db_fetch("SELECT * FROM sach WHERE id_sach = :id", [':id' => $id_sach]);

if (!$book) {
    echo "<div class='container' style='padding:50px; text-align:center;'><h3>Sách không tồn tại!</h3></div>";
    return;
}

// 2. Xử lý khi khách hàng gửi form Đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = h($_POST['ten_khach_hang']);
    $sao = intval($_POST['so_sao']);
    $noidung = h($_POST['noi_dung']);

    if (!empty($ten) && !empty($noidung)) {
        db_execute("INSERT INTO danh_gia (id_sach, ten_khach_hang, so_sao, noi_dung) VALUES (?, ?, ?, ?)", 
                  [$id_sach, $ten, $sao, $noidung]);
        echo "<script>alert('Cảm ơn bạn đã đánh giá!'); window.location.href='index.php?page=feedback&id=$id_sach';</script>";
    }
}

// 3. Lấy danh sách các đánh giá của cuốn sách này
$feedbacks = db_fetch_all("SELECT * FROM danh_gia WHERE id_sach = :id ORDER BY ngay_gui DESC", [':id' => $id_sach]);
?>

<link rel="stylesheet" href="assets/css/feedback.css">

<div class="home-section">
    <div class="section-header">
        <h2 class="section-title">Phản hồi về: <?= h($book['ten_sach']) ?></h2>
    </div>

    <div class="feedback-container">
        <div class="feedback-list">
            <h3>Cộng đồng nói gì?</h3>
            <?php if (empty($feedbacks)): ?>
                <p>Hãy là người đầu tiên đánh giá cuốn sách này!</p>
            <?php else: foreach ($feedbacks as $fb): ?>
                <div class="feedback-item">
                    <span class="feedback-user"><?= h($fb['ten_khach_hang']) ?></span>
                    <span class="feedback-stars"><?= str_repeat('⭐', $fb['so_sao']) ?></span>
                    <p class="feedback-content"><?= nl2br(h($fb['noi_dung'])) ?></p>
                    <span class="feedback-date"><?= date('d/m/Y H:i', strtotime($fb['ngay_gui'])) ?></span>
                </div>
            <?php endforeach; endif; ?>
        </div>

        <div class="feedback-form">
            <h3>Để lại ý kiến của bạn</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Tên của bạn</label>
                    <input type="text" name="ten_khach_hang" class="form-input" placeholder="Nhập tên..." required>
                </div>
                
                <div class="form-group">
                    <label>Bạn chấm mấy sao?</label>
                    <select name="so_sao" class="form-select">
                        <option value="5">5 Sao - Rất hài lòng</option>
                        <option value="4">4 Sao - Tốt</option>
                        <option value="3">3 Sao - Bình thường</option>
                        <option value="2">2 Sao - Tệ</option>
                        <option value="1">1 Sao - Rất tệ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nội dung nhận xét</label>
                    <textarea name="noi_dung" class="form-textarea" rows="6" placeholder="Cuốn sách này có gì hay?..." required></textarea>
                </div>
                <button type="submit" class="btn-submit-feedback">Gửi đánh giá ngay</button>
            </form>
        </div>
    </div>
</div>