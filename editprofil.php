<?php
require_once "koneksi.php";
require_once "query/pembeli.php";
session_start();

$db = (new Database())->connection();
$pembeli = new Pembeli($db);

// Ambil data untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $pembeli->cari($_GET['edit']);
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pembeli->idPembeli = $_POST['idPembeli'] ?? null;
    $pembeli->nama = $_POST['nama'];
    $pembeli->email = $_POST['email'];
    $pembeli->password = $_POST['password'];
    $pembeli->hp = $_POST['hp'];
    $pembeli->alamat = $_POST['alamat'];

    // Foto profil
    $fotoPath = $_POST['oldFoto'] ?? null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $targetDir = 'uploads/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $filename = time() . '_' . basename($_FILES['foto']['name']);
            $fotoPath = $targetDir . $filename;
            move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath);

            // Validasi apakah benar gambar
            if (!getimagesize($fotoPath)) {
                unlink($fotoPath); // hapus file jika bukan gambar
                $fotoPath = $_POST['oldFoto'] ?? 'gambar/default.png';
            }
        }
    }

    $pembeli->foto = $fotoPath;

    if (!empty($_POST['isEdit'])) {
        $pembeli->update();
    } else {
        $pembeli->create();
    }

    header("Location: profil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil | Es Teh AYA</title>
    <link rel="stylesheet" href="editprofil.css">
    <link rel="icon" href="gambar/LogoTeh.png">
    <style>
        .kotak-isian {
            width: 50%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            font-family: 'Times New Roman', Times, serif;
            margin-left: 350px;
            margin-top: 5px;
        }
        label {
            margin-left: 350px;
            display: block;
            margin-top: 15px;
        }
        .profil {
            margin-left: 485px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .profil img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #444;
        }
        .edit button {
            background-color: #87c346;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 16px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 50px;
        }
        h1 {
            text-align: center;
            color: #4c693e;
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
            </nav>
        </aside>
    <main class="content">
        <h1>Edit Profil</h1>
        <form action="editprofil.php" method="POST" enctype="multipart/form-data">
            <div class="profil">
                <img id="previewFoto" src="<?= !empty($editData['foto']) ? $editData['foto'] : 'gambar/default.png'; ?>" alt="Foto Profil">
                <div class="edit"><button type="submit">Simpan</button></div>
            </div>

            <?php if ($editData): ?>
                <input type="hidden" name="idPembeli" value="<?= $editData['idPembeli'] ?>">
                <input type="hidden" name="oldFoto" value="<?= $editData['foto'] ?>">
                <input type="hidden" name="isEdit" value="1">
            <?php endif; ?>

            <label for="foto">Foto Profil</label>
            <input type="file" name="foto" id="inputFoto" class="kotak-isian" accept="image/*">

            <label for="nama">Nama Akun</label>
            <input type="text" name="nama" class="kotak-isian" value="<?= $editData['nama'] ?? '' ?>">

            <label for="email">Email</label>
            <input type="text" name="email" class="kotak-isian" value="<?= $editData['email'] ?? '' ?>">

            <label for="password">Kata Sandi</label>
            <input type="text" name="password" class="kotak-isian" value="<?= $editData['password'] ?? '' ?>">

            <label for="hp">No HP</label>
            <input type="tel" name="hp" class="kotak-isian" value="<?= $editData['hp'] ?? '' ?>">

            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="kotak-isian"><?= htmlspecialchars($editData['alamat'] ?? '') ?></textarea>
        </form>
    </main>
    
    <footer><i>copyright by @EsTeh AYA</i></footer>
</div>

<script>
document.getElementById('inputFoto').addEventListener('change', function(event) {
    const input = event.target;
    const preview = document.getElementById('previewFoto');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
});
</script>
</body>
</html>
