<?php
$pageTitle = "Thêm sách";
$current_page = "sach";

require_once __DIR__ . '/../includes/header.php';

// Lấy danh mục & bộ sách
$danhmucs = db_fetch_all("SELECT * FROM danh_muc ORDER BY ten_danh_muc");
$bosachs  = db_fetch_all("SELECT * FROM bo_sach ORDER BY ten_bo_sach");

$success = $error = "";

// ==========================
//  HANDLE SUBMIT
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Kiểm tra mã sách trùng
    $check = db_fetch(
        "SELECT id FROM sach WHERE id_sach = :id_sach",
        [':id_sach' => $_POST['id_sach']]
    );

    if ($check) {
        $error = "❌ Mã sách đã tồn tại, vui lòng nhập mã khác!";
    } else {

        // 2. Xử lý upload ảnh
        $fileName = "";
        if (!empty($_FILES['hinh_anh']['name'])) {

            $fileName = time() . "_" . $_FILES['hinh_anh']['name'];
            $uploadPath = __DIR__ . "/../uploads/" . $fileName;

            if (!move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadPath)) {
                $error = "Lỗi upload ảnh!";
            }
        }

        // 3. Nếu không có lỗi thì INSERT
        if (!$error) {

            $sql = "
                INSERT INTO sach (
                    id_sach, id_danhmuc, id_bosach,
                    ten_sach, tac_gia, gia, ton_kho,
                    mo_ta, hinh_anh, trang_thai
                )
                VALUES (
                    :id_sach, :id_danhmuc, :id_bosach,
                    :ten_sach, :tac_gia, :gia, :ton_kho,
                    :mo_ta, :hinh_anh, :trang_thai
                )
            ";

            db_execute($sql, [
                ':id_sach'     => $_POST['id_sach'],
                ':id_danhmuc'  => $_POST['id_danhmuc'],
                ':id_bosach'   => !empty($_POST['id_bosach']) ? $_POST['id_bosach'] : NULL, // CHO PHÉP NULL
                ':ten_sach'    => $_POST['ten_sach'],
                ':tac_gia'     => $_POST['tac_gia'],
                ':gia'         => $_POST['gia'],
                ':ton_kho'     => $_POST['ton_kho'],
                ':mo_ta'       => $_POST['mo_ta'],
                ':hinh_anh'    => $fileName,
                ':trang_thai'  => $_POST['trang_thai']
            ]);

            $success = "✔ Thêm sách thành công!";
        }
    }
}
?>

<link rel="stylesheet" href="../assets/css/sach.css">

<div class="form-wrapper">

    <h2 class="form-title">Thêm sách mới</h2>

    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="book-form">

        <!-- ======== ẢNH SÁCH (BÊN TRÁI) ========= -->
        <div class="left-panel">
            <label class="label">Ảnh</label>

            <img id="preview" src="../assets/img/no-image.png" class="preview-img">

            <label class="upload-btn">
                <input type="file" name="hinh_anh" accept="image/*" onchange="loadImage(event)">
                Tải ảnh lên
            </label>
        </div>

        <!-- ======== FORM NHẬP LIỆU (BÊN PHẢI) ========= -->
        <div class="right-panel">

            <div class="form-row">
                <div class="form-group">
                    <label>Mã sách</label>
                    <input name="id_sach" placeholder="VD: S001" required>
                </div>

                <div class="form-group">
                    <label>Bộ sách (Tùy chọn)</label>
                    <select name="id_bosach">
                        <option value="">-- Không thuộc bộ sách --</option>
                        <?php foreach ($bosachs as $b): ?>
                            <option value="<?= $b['id_bosach'] ?>"><?= $b['ten_bo_sach'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group full">
                <label>Tên sách</label>
                <input name="ten_sach" required>
            </div>

            <div class="form-group full">
                <label>Tác giả</label>
                <input name="tac_gia" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Danh mục</label>
                    <select name="id_danhmuc" required>
                        <?php foreach ($danhmucs as $d): ?>
                            <option value="<?= $d['id_danhmuc'] ?>"><?= $d['ten_danh_muc'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Giá</label>
                    <input type="number" name="gia" min="0" placeholder="75000" required>
                </div>
            </div>

            <div class="form-group full">
                <label>Số lượng</label>
                <input type="number" name="ton_kho" required>
            </div>

            <div class="form-group full">
                <label>Mô tả</label>
                <textarea name="mo_ta"></textarea>
            </div>

            <div class="form-group full">
                <label>Trạng thái</label>
                <select name="trang_thai">
                    <option value="1">Hoạt động</option>
                    <option value="0">Ngừng kinh doanh</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="list.php" class="btn-cancel">Hủy</a>
                <button class="btn-submit">Lưu</button>
            </div>
        </div>

    </form>
</div>

<script>
function loadImage(e) {
    document.getElementById('preview').src = URL.createObjectURL(e.target.files[0]);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
