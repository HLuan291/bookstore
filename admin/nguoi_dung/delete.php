<?php
require_once __DIR__ . '/../includes/functions.php';

$id = intval($_GET['id'] ?? 0);

db_execute("DELETE FROM nguoi_dung WHERE id = :id", [':id' => $id]);

header("Location: list.php?p=deleted");
exit;
