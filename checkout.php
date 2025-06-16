<?php
session_start();
require_once "koneksi.php";
require_once "query/keranjangclass.php";
require_once "query/transaksi.php";
require_once "query/pembeli.php";


$db = (new Database())->connection();
$idPembeli = $_SESSION['idPembeli'];
$pembeli = new Pembeli($db);

$keranjang = new Keranjang($db);
$stmt = $keranjang->getByUser($idPembeli);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$transaksi = new Transaksi($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $metode = $_POST['metode'] ?? '';

    // Hitung total seluruh belanja
    $totalCheckout = 0;
    foreach ($items as $item) {
        $totalCheckout += $item['harga'] * $item['jumlah'];
    }

    // Buat transaksi utama dan ambil idtransaksi
    $idTransaksi = $transaksi->createMainTransaksi($idPembeli, $totalCheckout);

    // Simpan semua item ke tabel transaksi_detail
    foreach ($items as $item) {
    $transaksi->addDetail(
        $idTransaksi,
        $item['idmenu'],
        $item['jumlah'],
        $item['harga']
    );
}


    // Hapus isi keranjang setelah checkout
    $db->prepare("DELETE FROM keranjang WHERE idPembeli = ?")->execute([$idPembeli]);

    header("Location: beranda.php?status=checkout_berhasil");
    exit;
}

$data = $pembeli->readById($_SESSION['idPembeli']);
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout | Es Teh AYA</title>
  <link rel="stylesheet" href="checkout.css" />
  <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20" />
  <style>
    .payment-form input,
.payment-form textarea,
.payment-form select {
  font-family: Arial, sans-serif;
  font-size: 16px;
  padding: 10px;
  width: 100%;
  box-sizing: border-box;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
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

.btn-hijau {
    background-color: #87c346;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-top: 20px;
}
.btn-hijau:hover {
    background-color: #6da82d;
}
  </style>
</head>
<body>
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
      <h1>Checkout</h1>
      <div class="checkout-container">
        <section id="checkout-items">
          <section class="checkout-list">
  <?php
  $totalCheckout = 0;
  foreach ($items as $item):
    $subtotal = $item['harga'] * $item['jumlah'];
    $totalCheckout += $subtotal;
  ?>
    <div class="keranjang-item">
      <img src="<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['namamenu']) ?>" width="80" />
      <div class="info">
        <strong><?= htmlspecialchars($item['namamenu']) ?></strong><br />
        Jumlah: <?= (int)$item['jumlah'] ?><br />
        <b>Total: Rp.<?= number_format($subtotal, 0, ',', '.') ?></b>
      </div>
    </div>
  <?php endforeach; ?>
</section>
<div id="total-checkout">
  <h3>Total Belanja: Rp.<?= number_format($totalCheckout, 0, ',', '.') ?></h3>
</div>

        </section>
        <?php if ($row = $data->fetch(PDO::FETCH_ASSOC)) : ?>
        <form class="payment-form" method="POST">
          <label for="nama">Nama Lengkap</label>
         <input type="text" id="nama" name="nama" required value="<?= htmlspecialchars($row['nama'] ?? '') ?>" />

          <label for="alamat">Alamat Pengiriman</label>
         <textarea id="alamat" name="alamat" required><?= htmlspecialchars($row['alamat'] ?? '') ?></textarea>

          <label for="metode">Metode Pembayaran</label>
          <select id="metode" required>
            <option value="">Pilih Metode</option>
            <option value="cod">COD (Bayar di Tempat)</option>
            <option value="transfer">Transfer Bank</option>
          </select>

          <button type="submit" class="btn-hijau">Bayar Sekarang</button>
        </form>
      </div>
      <?php else: ?>
                    <p>Data tidak ditemukan.</p>
                <?php endif; ?>
    </main>

    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
  </html>
