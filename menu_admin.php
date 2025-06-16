<?php
session_start();
if (!isset($_SESSION['idPembeli'])) {
    header("Location: login.php");
    exit;
}

require_once "koneksi.php";
require_once "query/menu.php";

$db = (new Database())->connection();
$menu = new Menu($db);
$data = $menu->readAll();
$isAdmin = $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Menu | Es Teh AYA</title>
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20" />
    <link rel="stylesheet" href="menu_admin.css" />

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
            </nav>
        </aside>

        <main class="content">
            <div class="header">
                <a href="input_menu.php" class="button" style="background-color: #87c346">Input Menu</a>
            </div>

            <div class="menu-grid">
                <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="item">
                        <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['namamenu']) ?>" />
                        <div><strong><?= htmlspecialchars($row['namamenu']) ?></strong></div>
                        <div>Rp<?= number_format($row['harga'], 0, ',', '.') ?></div>

                        <?php if ($isAdmin): ?>
                            <div class="admin-buttons">
                                <button class="edit-btn" onclick="editMenu(<?= $row['idmenu'] ?>)" style="background-color: #87c346">Edit</button>
                               <button class="delete-btn" onclick="hapusMenu(<?= $row['idmenu'] ?>)" data-id="<?= $row['idmenu'] ?>">Hapus</button>
                            </div>
                        <?php else: ?>
                            <button class="add-to-cart" data-id="<?= $row['idmenu'] ?>" data-nama="<?= htmlspecialchars($row['namamenu']) ?>">
                                Tambah
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <footer><i>copyright by @EsTeh AYA</i></footer>

    <script>
    function editMenu(id) {
        window.location.href = 'edit_menu.php?id=' + id;
    }

    function hapusMenu(id) {
        if (confirm('Yakin ingin menghapus menu ini?')) {
            fetch('hapus_menu.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ idmenu: id })
            })
            .then(res => res.text())
            .then(data => {
                alert(data);
                const item = document.querySelector(`.item button.delete-btn[data-id='${id}']`)?.closest('.item');
                if (item) item.remove();
            })
            .catch(err => alert('Terjadi kesalahan: ' + err));
        }
    }

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.dataset.id = button.getAttribute('onclick').match(/\d+/)[0];
    });

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