<?php
require_once "koneksi.php";
require_once "query/pembeli.php";

$db = (new Database())->connection();
$pembeli = new Pembeli($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pembeli->idPembeli = $_POST['idPembeli'] ?? null;
    $pembeli->nama = $_POST['nama'];
    $pembeli->email = $_POST['email'];
    $pembeli->password = $_POST['password'];
    $pembeli->hp = $_POST['hp'];
    $pembeli->alamat = $_POST['alamat'];

    if (!empty($_POST['isEdit'])) {
        $pembeli->update();
    } else {
        $pembeli->create();
    }

    header("location:login.php");
    exit;
}

$editData = null;

if (isset($_GET['edit'])) {
    $editData = $pembeli->cari($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar | Es Teh AYA</title>
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20">
    <link rel="stylesheet" href="daftar.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <img src="gambar/LogoTeh.png" alt="Logo Es Teh AYA">
            </div>
            <nav>
                <ul class="nav-horizontal">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <div class="form-box">
                <h2>Akun Baru</h2>
                <form id="daftarForm" method="POST">
                    <?php if ($editData): ?>
                        <input type="hidden" name="idPembeli" value="<?= $editData['idPembeli'] ?>">
                    <?php endif; ?>

                    <label for="nama">Nama Akun</label>
                    <input type="text" name="nama" id="nama" value="<?= $editData['nama'] ?? '' ?>" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= $editData['email'] ?? '' ?>" required>

                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" value="<?= $editData['password'] ?? '' ?>" required>

                    <label for="hp">No. HP</label>
                    <input type="tel" name="hp" id="hp" value="<?= $editData['hp'] ?? '' ?>" required>

                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="kotak-isian" required><?= $editData['alamat'] ?? '' ?></input>

                    <?php if ($editData): ?>
                        <input type="hidden" name="isEdit" value="1">
                    <?php endif; ?>

                    <input type="submit" name="daftar" value="<?= $editData ? 'Perbarui' : 'Daftar' ?>">
                </form>
            </div>
        </main>
    </div>
    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>
