<?php
// This is a placeholder
// Users should access /admin/dangnhap.php or /client/index.php directly
// Or configure your web server to use this as the entry point

// For WAMP/local development, just redirect
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If accessing root, go to client
if ($uri === '/' || $uri === '/bookstore/') {
    include __DIR__ . '/../client/index.php';
} elseif (strpos($uri, '/admin') !== false) {
    // Admin requests
    include __DIR__ . '/../admin' . substr($uri, strpos($uri, '/admin') + 6);
} else {
    // Client requests
    include __DIR__ . '/../client/index.php';
}
?>
