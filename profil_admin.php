<?php
    require_once "koneksi.php";
    require_once "query/pembeli.php";
    require_once "query/transaksi.php";

    session_start();

    $db = (new Database())->connection();
    $pembeli = new Pembeli($db);

    if(isset($_GET['delete'])) {
        $pembeli->idPembeli = $_GET['delete'];
        $pembeli->delete();
        header("location:profil_admin.php");
        exit;
    }

    $data = $pembeli->readAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profil | Es Teh AYA</title>
    <link rel="stylesheet" href="profil_admin.css">
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20" />

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
<body onscroll="console.log(document.body.scrollLeft)">
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
            </nav>
        </aside>

        <main class="content">
            <div class="card-container">
                <?php while ($row = $data->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="pembeli-card">
                <h3><?php echo $row['nama']; ?></h3>
                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                <p><strong>No HP:</strong> <?php echo $row['hp']; ?></p>
                <p><strong>Alamat :</strong> <?php echo $row['alamat']; ?></p>
                <div class="action">
                    <a href="profil_admin.php?delete=<?php echo $row['idPembeli']; ?>" onclick="return confirm('Yakin ingin menghapus pembeli ini?')">Hapus</a>
                </div>
                </div>
            <?php endwhile; ?>
            </div>
        </main>
    </div>
    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>