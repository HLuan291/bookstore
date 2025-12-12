<?php
// admin/includes/functions.php

// KẾT NỐI CSDL DÙNG CHUNG CHO TOÀN BỘ ADMIN
// -----------------------------------------------
function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        // config/database.php: return ['host' => '...', 'dbname' => '...', 'user' => '...', 'pass' => '...'];
        $config = require __DIR__ . '/../../config/database.php';

        $host   = $config['host']   ?? 'localhost';
        $dbname = $config['dbname'] ?? 'bookstore';
        $user   = $config['user']   ?? 'root';
        $pass   = $config['pass']   ?? '';

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Lỗi kết nối CSDL: ' . $e->getMessage());
        }
    }

    return $pdo;
}

// HÀM HỖ TRỢ TRUY VẤN CSDL
// -----------------------------------------------

/**
 * SELECT nhiều dòng → trả về mảng các dòng
 */
function db_fetch_all(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * SELECT 1 dòng → trả về mảng hoặc null
 */
function db_fetch(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * INSERT / UPDATE / DELETE
 * Trả về số dòng bị ảnh hưởng
 */
function db_execute(string $sql, array $params = []): int
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * Lấy ID tự tăng mới nhất sau khi INSERT
 */
function db_last_insert_id(): string
{
    return db()->lastInsertId();
}

// HÀM TIỆN ÍCH CHUNG
// -----------------------------------------------

/**
 * Escape HTML (chống XSS) – dùng khi echo dữ liệu ra view
 */
function e(?string $str): string
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Lấy tham số GET với giá trị mặc định
 */
function get_param(string $name, $default = null)
{
    return $_GET[$name] ?? $default;
}

/**
 * Chuyển hướng và dừng script
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

/**
 * Xây lại query string với việc thay đổi / thêm param
 * Ví dụ dùng cho phân trang, giữ lại keyword:
 * build_url(['p' => 2])
 */
function build_url(array $extra = []): string
{
    $params = $_GET;
    foreach ($extra as $k => $v) {
        if ($v === null) {
            unset($params[$k]);
        } else {
            $params[$k] = $v;
        }
    }
    $query = http_build_query($params);
    return '?' . $query;
}
