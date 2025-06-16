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
require_once "query/menu.php";
require_once "query/keranjangclass.php";

$db = (new Database())->connection();
$menu = new Menu($db);
$data = $menu->readAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Menu | Es Teh AYA</title>
    <link rel="stylesheet" href="daftarmenu.css" />
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20" />

    <style>
        .add-to-cart {
            background-color: #87c346; 
            color: white;
            border: none;
            border-radius: 5px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 8px;
            transition: background-color 0.3s ease;
        }

        .add-to-cart:hover {
        background-color: #218838; 
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
                <img src="gambar/LogoTeh.png" alt="Logo Es Teh AYA" />
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
            <h1>Menu Es Teh AYA</h1>
            <p>Berbagai menu Es Teh AYA yang siap menghilangkan rasa haus anda</p>

            <div class="menu-grid">
                <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="item">
                        <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['namamenu']) ?>" />
                        <div><?= htmlspecialchars($row['namamenu']) ?></div>
                        <div>Rp<?= number_format($row['harga'], 0, ',', '.') ?></div>
                        <button class="add-to-cart" data-id="<?= $row['idmenu'] ?>" data-nama="<?= htmlspecialchars($row['namamenu']) ?>">
                            Tambah
                        </button>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>
    <footer><i>copyright by @EsTeh AYA</i></footer>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nama = button.dataset.nama;

                if (confirm(`Tambah "${nama}" ke keranjang?`)) {
                    fetch('keranjang.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams({idmenu: id})
                    })
                    .then(res => res.text())
                    .then(data => alert(data))
                    .catch(err => alert('Terjadi kesalahan: ' + err));
                }
            });
        });
    </script>
</body>
</html>
