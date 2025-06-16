<?php
session_start();
if (!isset($_SESSION['idPembeli']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

require_once "koneksi.php";
require_once "query/menu.php";

$db = (new Database())->connection();
$menu = new Menu($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idmenu'] ?? null;

    if ($id && $menu->delete($id)) {
        echo "Menu berhasil dihapus.";
    } else {
        http_response_code(500);
        echo "Gagal menghapus menu.";
    }
}
