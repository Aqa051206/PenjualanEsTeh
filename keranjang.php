<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['idPembeli'])) {
    header("Location: login.php");
    exit;
}

require_once "koneksi.php";
require_once "query/keranjangclass.php";

$idPembeli = $_SESSION['idPembeli'];
$db = (new Database())->connection();
$keranjang = new Keranjang($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idmenu = $_POST['idmenu'] ?? null;

    // AJAX dari daftarmenu.php
    if (!isset($_POST['aksi']) && $idmenu) {
        $berhasil = $keranjang->add($idPembeli, $idmenu);
        if ($berhasil) {
            echo "Berhasil ditambahkan ke keranjang";
        } else {
            echo "Gagal menambahkan ke keranjang";
        }
        exit;
    }

    // Penanganan aksi tambah/kurang/hapus dari halaman keranjang
    if (isset($_POST['aksi']) && $idmenu) {
        $aksi = $_POST['aksi'];

        if ($aksi === 'hapus') {
            $keranjang->delete($idPembeli, $idmenu);
        } else {
            $stmt = $keranjang->getByUser($idPembeli);
            while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($item['idmenu'] == $idmenu) {
                    $jumlah = $item['jumlah'];
                    if ($aksi === 'tambah') {
                        $jumlah++;
                    } elseif ($aksi === 'kurang' && $jumlah > 1) {
                        $jumlah--;
                    }
                    $keranjang->updateJumlah($idPembeli, $idmenu, $jumlah);
                    break;
                }
            }
        }
        header("Location: keranjang.php");
        exit;
    }
}
$stmt = $keranjang->getByUser($idPembeli);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Keranjang | Es Teh AYA</title>
    <link rel="stylesheet" href="keranjang.css" />
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20" />
    <style>
        .tombol-checkout {
    display: inline-block;
    background-color: #87c346;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 16px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
    text-align: center;
}

.tombol-checkout:hover {
    background-color: #388e3c;
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
    <div class="wrapper">
        <div class="container">
            <aside class="sidebar">
                <div class="profile">
                    <img src="gambar/LogoTeh.png" alt="Foto Profil" />
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
                </nav>
            </aside>

            <main class="content">
                <h1>Keranjang</h1>
                <?php 
                    $totalSemua = 0;
                    foreach ($items as $item): 
                    $subtotal = $item['harga'] * $item['jumlah'];
                    $totalSemua += $subtotal;
                ?>
                    <div class="keranjang-item">
                        <img src="<?= htmlspecialchars($item['gambar']) ?>" width="80" alt="<?= htmlspecialchars($item['namamenu']) ?>" />
                    <div>
                    <strong><?= htmlspecialchars($item['namamenu']) ?></strong><br />
                        Harga: Rp<?= number_format($item['harga'], 0, ',', '.') ?><br />
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="idmenu" value="<?= $item['idmenu'] ?>">
                        <button type="submit" name="aksi" value="kurang">-</button>
                    </form>
                    <span><?= (int)$item['jumlah'] ?></span>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="idmenu" value="<?= $item['idmenu'] ?>">
                        <button type="submit" name="aksi" value="tambah">+</button>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="idmenu" value="<?= $item['idmenu'] ?>">
                            <button type="submit" name="aksi" value="hapus" onclick="return confirm('Yakin ingin menghapus item ini dari keranjang?')">Hapus</button>
                        </form>
                    </form>
                    <br />
                    Subtotal: Rp<?= number_format($subtotal, 0, ',', '.') ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <h3>Total Keseluruhan: Rp<?= number_format($totalSemua, 0, ',', '.') ?></h3>
                <a href="checkout.php" class="tombol-checkout">Checkout Sekarang</a>

            </main>
        </div>
    </div>

    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>