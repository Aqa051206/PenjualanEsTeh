<?php
session_start();
if (!isset($_SESSION['idPembeli']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once "koneksi.php";
require_once "query/menu.php";

$db = (new Database())->connection();
$menu = new Menu($db);
$message = "";
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namamenu = trim($_POST['namamenu']);
    $harga = (int) $_POST['harga'];
    $gambarPath = "";

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'gambar/';
        $fileName = basename($_FILES['gambar']['name']);
        $targetPath = $uploadDir . time() . '_' . $fileName;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
            $gambarPath = $targetPath;
        } else {
            $message = "Gagal mengunggah gambar.";
        }
    }

    if ($namamenu && $harga && $gambarPath) {
        $menu->namamenu = $namamenu;
        $menu->harga = $harga;
        $menu->gambar = $gambarPath;

        if ($menu->create()) {
            header("Location: daftarmenu.php"); // redirect ke daftar menu
            exit;
        } else {
            $message = "Gagal menambahkan menu.";
        }
    } else {
        $message = "Semua field harus diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu | Es Teh AYA</title>
    <link rel="stylesheet" href="input_menu.css">
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20">
    <style>
        .btn-primary {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    margin-right: 10px;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #45a049;
}

.btn-secondary {
    background-color: #f44336;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.btn-secondary:hover {
    background-color: #d32f2f;
}

.nav-horizontal {
    display: flex;
    gap: 3px;
    list-style: none;
    padding: 0;
   margin-right: 59px;
    flex-wrap: nowrap;
    white-space: nowrap;
}
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <img src="gambar/LogoTeh.png" alt="Logo Es Teh AYA">
            </div>
            <nav>
                <ul class="nav-horizontal">
                    <li><a href="beranda.php">Beranda</a></li>
                    <li><a href="daftarmenu.php">Menu</a></li>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="keranjang.php">Keranjang</a></li>
                    <?php if ($_SESSION['role'] === 'admin') : ?>
                        <li><a href="menu_admin.php"> View Menu </a></li>
                        <li><a href="profil_admin.php"> View Profil </a></li>
                        <li><a href="transaksi_admin.php"> View Transaksi </a></li>
                    <?php endif; ?>
                    <li><a href="index.php">Keluar</a></li>    
                </ul>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <div class="form-wrapper">
                <h1>Tambah Menu Baru</h1>
                <p>Silakan tambah menu berikut:</p>

                <?php if ($message): ?>
                    <div class="message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
            <label for="namamenu">Nama Menu:</label>
            <input type="text" id="namamenu" name="namamenu" required>

            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" required>

            <label for="gambar">Gambar:</label>
            <input type="file" id="gambar" name="gambar" accept="image/*" required>

            <div class="form-buttons">
                <button type="submit" class="btn-primary">Simpan</button>
                <a href="daftarmenu.php" class="btn-secondary">Kembali</a>
            </div>

        </form>
            </div>
        </main>
    </div>

     <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>