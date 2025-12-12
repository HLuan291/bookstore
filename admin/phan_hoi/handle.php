<?php
require_once __DIR__ . '/../includes/functions.php';

$action = $_GET["action"] ?? "";
$id = intval($_GET["id"] ?? 0);

if ($action === "delete") {
    db_execute("DELETE FROM phan_hoi WHERE id = :id", [":id" => $id]);
    header("Location: list.php?msg=deleted");
    exit;
}

header("Location: list.php");
exit;
