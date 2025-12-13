<?php
$pageTitle = "Sửa bộ sách";
require_once __DIR__ . '/../includes/header.php';
// ... (Phần xử lý PHP không thay đổi) ...
// $id, $bosach, $sachs, $danhmucs, $success được giữ nguyên
// ... (Phần xử lý POST không thay đổi) ...
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/bo_sach.css">
<div class="form-wrapper"> 
    
    <h1 class="form-title">Sửa Bộ Sách</h1>

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
    
    <form method="post" class="book-form">
        
        <div class="left-panel">
            <label class="upload-btn">
                Chọn Ảnh
                <input type="file" name="image" style="display: none;">
            </label>
            </div>

        <div class="right-panel">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Mã bộ sách:</label>
                    <input name="id_bosach" value="<?= $bosach['id_bosach'] ?>">
                </div>
                <div class="form-group">
                    <label>Tên bộ sách:</label>
                    <input name="ten_bo_sach" value="<?= $bosach['ten_bo_sach'] ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Sách:</label>
                    <select name="id_sach">
                        <?php foreach ($sachs as $s): ?>
                            <option value="<?= $s['id_sach'] ?>" <?= $bosach['id_sach'] == $s['id_sach'] ? 'selected' : '' ?>>
                                <?= $s['ten_sach'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Danh mục:</label>
                    <select name="id_danhmuc">
                        <?php foreach ($danhmucs as $d): ?>
                            <option value="<?= $d['id_danhmuc'] ?>" <?= $bosach['id_danhmuc'] == $d['id_danhmuc'] ? 'selected' : '' ?>>
                                <?= $d['ten_danh_muc'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full">
                    <label>Giá:</label>
                    <input name="gia" type="number" value="<?= $bosach['gia'] ?>">
                </div>
            </div>
            
 <div class="form-actions">
            <a href="list.php" class="btn-cancel">Hủy</a>
            <button class="btn-add">Lưu</button>
        </div>

        </div> </form>
</div> <?php include __DIR__ . '/../includes/footer.php'; ?>