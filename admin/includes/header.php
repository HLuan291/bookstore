<?php
session_start();
require_once __DIR__ . '/functions.php';

// Check if admin is logged in (skip check for login pages)
$current_file = basename($_SERVER['PHP_SELF']);
$login_pages = ['dangnhap.php', 'dangky.php'];

if (!isset($_SESSION['admin']) && !in_array($current_file, $login_pages)) {
    header("Location: dangnhap.php");
    exit;
}

$pageTitle = $pageTitle ?? "Admin Panel";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/admin/assets/css/sidebar.css">
</head>
<body>

<div class="admin-container">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
