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

if (!$id) {
    header("Location: daftarmenu.php");
    exit;
}

$data = $menu->getById($id);
if (!$data) {
    $message = "Menu tidak ditemukan.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namamenu = trim($_POST['namamenu']);
    $harga = (int) $_POST['harga'];
    $gambarPath = $data['gambar'];

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
        $menu->idmenu = $id;
        $menu->namamenu = $namamenu;
        $menu->harga = $harga;
        $menu->gambar = $gambarPath;

        if ($menu->update()) {
            header("Location: daftarmenu.php");
            exit;
        } else {
            $message = "Gagal memperbarui menu.";
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
     <link rel="stylesheet" href="edit_menu.css">
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20">

    <style>
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
                <h1>Edit Menu</h1>
                <p>Silakan ubah informasi menu berikut:</p>

                <?php if ($message): ?>
                    <div class="message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <label>Nama Menu:</label>
                    <input type="text" name="namamenu" value="<?= htmlspecialchars($data['namamenu']) ?>" required>

                    <label>Harga (Rp):</label>
                    <input type="number" name="harga" value="<?= (int)$data['harga'] ?>" required>

                    <label>Gambar Sekarang:</label><br>
                    <img src="<?= htmlspecialchars($data['gambar']) ?>" width="150"><br>

                    <label>Ganti Gambar (Opsional):</label>
                    <input type="file" name="gambar" accept="image/*">

                    <div class="form-buttons">
                        <button type="submit" class="edit-btn">Simpan</button>
                        <a href="daftarmenu.php" class="delete-btn">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

     <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>