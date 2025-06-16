<?php
    require_once "koneksi.php";
    require_once "query/pembeli.php";
    require_once "query/transaksi.php";

    session_start();

    $db = (new Database())->connection();

    $transaksi = new Transaksi($db);
    $transaksi = $transaksi->readAllTransaksiWithDetails();
    $pembeli = new Pembeli($db);

    if(isset($_GET['delete'])) {
        $pembeli->idPembeli = $_GET['delete'];
        $pembeli->delete();
        header("location:transaksi_admin.php");
        exit;
    }

    $data = $pembeli->readAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Transaksi | Es Teh AYA</title>
    <link rel="stylesheet" href="transaksi_admin.css">
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20" />

    <style>
      .transaksi-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
  margin-top: 40px;
}


.transaksi-card {
  background-color: #f7f7f7;
  border-left: 5px solid #87c346;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
  width: 300px;
}


.transaksi-card h3 {
  margin: 0 0 10px;
}

.menu-item {
  margin-left: 20px;
  padding: 6px 0;
  border-bottom: 1px solid #ddd;
  font-size: 15px;
}

.menu-item:last-child {
  border-bottom: none;
}

.total-harga {
  text-align: right;
  font-weight: bold;
  margin-top: 10px;
  color: #444;
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
    <h2 style="margin-top: 50px; color: #4c693e">Riwayat Transaksi</h2>
    <div class="transaksi-container">
    <?php 
    $grouped = [];
    while ($row = $transaksi->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['idtransaksi'];
        if (!isset($grouped[$id])) {
            $grouped[$id] = [
                'nama' => $row['nama'],
                'items' => [],
                'total' => 0
            ];
        }
        $grouped[$id]['items'][] = [
            'namamenu' => $row['namamenu'],
            'jumlah' => $row['jumlah'],
            'hargasaatitu' => $row['hargasaatitu'],
            'subtotal' => $row['subtotal']
        ];
        $grouped[$id]['total'] += $row['subtotal'];
    }

    foreach ($grouped as $id => $data):
    ?>
      <div class="transaksi-card">
        <h3>ID Transaksi: <?= $id ?></h3>
        <p><strong>Pembeli:</strong> <?= htmlspecialchars($data['nama']) ?></p>
        <?php foreach ($data['items'] as $item): ?>
          <div class="menu-item">
            <?= htmlspecialchars($item['namamenu']) ?> - <?= $item['jumlah'] ?> x Rp.<?= number_format($item['hargasaatitu'], 0, ',', '.') ?> = <strong>Rp.<?= number_format($item['subtotal'], 0, ',', '.') ?></strong>
          </div>
        <?php endforeach; ?>
        <div class="total-harga">Total: Rp.<?= number_format($data['total'], 0, ',', '.') ?></div>
      </div>
    <?php endforeach; ?>
    </div>
</main>

    </div>
    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>