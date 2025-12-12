<?php
$pageTitle = "Sửa sách";
$current_page = "sach";
require_once __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
$book = db_fetch("SELECT * FROM sach WHERE id = :id", [':id' => $id]);

if (!$book) die("Không tìm thấy sách");

$danhmucs = db_fetch_all("SELECT * FROM danh_muc ORDER BY ten_danh_muc");
$bosachs  = db_fetch_all("SELECT * FROM bo_sach ORDER BY ten_bo_sach");

$success = "";
$error   = "";

// ===============================
// XỬ LÝ SUBMIT
// ===============================
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Nếu không chọn bộ sách thì để NULL
    $idBosach = !empty($_POST['id_bosach']) ? $_POST['id_bosach'] : NULL;

    // Upload ảnh mới nếu có
    $setImage = "";
    if (!empty($_FILES['hinh_anh']['name'])) {
        $filename = time() . "_" . $_FILES['hinh_anh']['name'];
        $uploadPath = __DIR__ . "/../uploads/" . $filename;

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $uploadPath)) {
            $setImage = ", hinh_anh = :hinh_anh";
        } else {
            $error = "Lỗi upload ảnh!";
        }
    }

    if (!$error) {

        $sql = "
            UPDATE sach SET 
                id_sach    = :id_sach,
                ten_sach   = :ten_sach,
                id_danhmuc = :id_danhmuc,
                id_bosach  = :id_bosach,
                tac_gia    = :tac_gia,
                gia        = :gia,
                ton_kho    = :ton_kho,
                mo_ta      = :mo_ta,
                trang_thai = :trang_thai
                $setImage
            WHERE id = :id
        ";

        $params = [
            ':id'        => $id,
            ':id_sach'   => $_POST['id_sach'],
            ':ten_sach'  => $_POST['ten_sach'],
            ':id_danhmuc'=> $_POST['id_danhmuc'],
            ':id_bosach' => $idBosach,   // CHO PHÉP NULL
            ':tac_gia'   => $_POST['tac_gia'],
            ':gia'       => $_POST['gia'],
            ':ton_kho'   => $_POST['ton_kho'],
            ':mo_ta'     => $_POST['mo_ta'],
            ':trang_thai'=> $_POST['trang_thai']
        ];

        if ($setImage) {
            $params[':hinh_anh'] = $filename;
        }

        db_execute($sql, $params);

        $success = "Cập nhật thành công!";
        $book = db_fetch("SELECT * FROM sach WHERE id = :id", [':id' => $id]); // Load lại dữ liệu mới
    }
}
?>
<link rel="stylesheet" href="../assets/css/sach.css">
<div class="form-page">
    <h1>Sửa Sách</h1>

    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

<form method="post" enctype="multipart/form-data" class="form-admin">

    <label>Mã sách:</label>
    <input name="id_sach" value="<?= $book['id_sach'] ?>">

    <label>Tên sách:</label>
    <input name="ten_sach" value="<?= $book['ten_sach'] ?>">

    <label>Danh mục:</label>
    <select name="id_danhmuc">
        <?php foreach ($danhmucs as $d): ?>
            <option value="<?= $d['id_danhmuc'] ?>" 
                <?= $book['id_danhmuc'] == $d['id_danhmuc'] ? 'selected' : '' ?>>
                <?= $d['ten_danh_muc'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Bộ sách (Tùy chọn):</label>
    <select name="id_bosach">
        <option value="">-- Không thuộc bộ sách --</option>
        <?php foreach ($bosachs as $b): ?>
            <option value="<?= $b['id_bosach'] ?>"
                <?= ($book['id_bosach'] ?? '') == $b['id_bosach'] ? 'selected' : '' ?>>
                <?= $b['ten_bo_sach'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Tác giả:</label>
    <input name="tac_gia" value="<?= $book['tac_gia'] ?>">

    <label>Giá:</label>
    <input type="number" name="gia" value="<?= $book['gia'] ?>">

    <label>Tồn kho:</label>
    <input type="number" name="ton_kho" value="<?= $book['ton_kho'] ?>">

    <label>Mô tả:</label>
    <textarea name="mo_ta"><?= $book['mo_ta'] ?></textarea>

    <label>Ảnh hiện tại:</label><br>
    <img src="../uploads/<?= $book['hinh_anh'] ?>" width="150" style="border-radius:6px;margin-bottom:8px">

    <label>Đổi ảnh mới:</label>
    <input type="file" name="hinh_anh">

    <label>Trạng thái:</label>
    <select name="trang_thai">
        <option value="1" <?= $book['trang_thai']==1?'selected':'' ?>>Hoạt động</option>
        <option value="0" <?= $book['trang_thai']==0?'selected':'' ?>>Ngừng</option>
    </select>

    <button class="btn-save">Lưu thay đổi</button>

</form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
