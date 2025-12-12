<?php
// ===================================
// KẾT NỐI CSDL CHO CLIENT
// ===================================

function db_client(): PDO
{
    static $pdo = null;

    if ($pdo === null) {

        // Load config chung
        $config = require __DIR__ . '/../../config/database.php';

        $host   = $config['host'];
        $dbname = $config['dbname'];
        $user   = $config['user'];
        $pass   = $config['pass'];

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL (client): " . $e->getMessage());
        }
    }

    return $pdo;
}

// ===================================
// QUERY HỖ TRỢ
// ===================================

function db_all(string $sql, array $params = []): array
{
    $stmt = db_client()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function db_one(string $sql, array $params = []): ?array
{
    $stmt = db_client()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

function db_run(string $sql, array $params = []): int
{
    $stmt = db_client()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

// Escape HTML
function h($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// ===================================
// HÀM XỬ LÝ DỮ LIỆU SÁCH & DANH MỤC
// ===================================

// Lấy danh mục
function get_categories()
{
    return db_all("SELECT * FROM danh_muc ORDER BY ten_danh_muc ASC");
}

// Lấy tất cả sách
function get_books()
{
    return db_all("SELECT * FROM sach ORDER BY id_sach DESC");
}

// Lấy sách theo danh mục
function get_books_by_cat($id)
{
    return db_all("
        SELECT * FROM sach 
        WHERE id_danhmuc = :id
        ORDER BY id_sach DESC
    ", [":id" => $id]);
}

// Lấy 1 sách
function get_book($id)
{
    return db_one("
        SELECT * FROM sach 
        WHERE id_sach = :id
    ", [":id" => $id]);
}

// ===================================
// HỖ TRỢ SINH MÃ ĐƠN HÀNG
// ===================================
function generate_order_id()
{
    $last = db_one("
        SELECT id_donhang 
        FROM don_hang 
        ORDER BY id DESC 
        LIMIT 1
    ");

    if ($last) {
        $num = intval(substr($last['id_donhang'], 2)) + 1;
    } else {
        $num = 1;
    }

    return "DH" . str_pad($num, 3, "0", STR_PAD_LEFT);
}

// ===================================
// GIỎ HÀNG (SESSION)
// ===================================
function cart_add($book_id, $qty = 1)
{
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (!isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id] = $qty;
    } else {
        $_SESSION['cart'][$book_id] += $qty;
    }
}

function cart_update($book_id, $qty)
{
    if ($qty <= 0) {
        unset($_SESSION['cart'][$book_id]);
    } else {
        $_SESSION['cart'][$book_id] = $qty;
    }
}

function cart_remove($book_id)
{
    unset($_SESSION['cart'][$book_id]);
}

function cart_clear()
{
    unset($_SESSION['cart']);
}

function cart_items()
{
    return $_SESSION['cart'] ?? [];
}
// SHIM để hỗ trợ code cũ
function db_fetch_all($sql, $params = [])
{
    return db_all($sql, $params);
}

function db_fetch($sql, $params = [])
{
    return db_one($sql, $params);
}

function db_execute($sql, $params = [])
{
    return db_run($sql, $params);
}

?>
