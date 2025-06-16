<?php
    require_once "koneksi.php";
    require_once "query/pembeli.php";
    session_start();

    $db = (new Database())->connection();
    $pembeli = new Pembeli($db);

    if(isset($_GET['delete'])) {
        $pembeli->idPembeli = $_GET['delete'];
        $pembeli->delete();
        header("location:profil.php");
        exit;
    }

    $data = $pembeli->readById($_SESSION['idPembeli']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | Es Teh AYA</title>
    <link rel="stylesheet" href="profil.css">
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

    .edit button {
        background-color: #87c346;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 14px;
        margin-top: -10px;
        transition: background-color 0.3s ease;
        width: 125px;
    }

    .edit button:hover {
        background-color: #218838;
    }

    .avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #444;
        margin-bottom: 16px;
    }
</style>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <img src="gambar/LogoTeh.png" alt="Foto Profil">
            </div>
            <nav>
                <ul class="nav-horizontal">
                    <li><a href="beranda.php">Beranda</a></li>
                    <li><a href="daftarmenu.php">Menu</a></li>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="keranjang.php">Keranjang</a></li>
                    <?php if ($_SESSION['role'] === 'admin') : ?>
                        <li><a href="menu_admin.php">View Menu</a></li>
                        <li><a href="profil_admin.php"> View Profil </a></li>
                        <li><a href="transaksi_admin.php"> View Transaksi </a></li>
                    <?php endif; ?>
                    <li><a href="index.php">Keluar</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <header>
                <h1>Profil Pengguna</h1>
            </header>
            <section>
                <?php if ($row = $data->fetch(PDO::FETCH_ASSOC)) : ?>
    <div class="profil">
        <?php
        $fotoPath = !empty($row['foto']) ? $row['foto'] : "gambar/default.png";
        ?>
        <img src="<?php echo $fotoPath; ?>" alt="Foto Profil" class="avatar">

        <div class="edit">
            <form action="editprofil.php" method="get">
                <input type="hidden" name="edit" value="<?php echo $row['idPembeli']; ?>">
                <button type="submit" class="btn-edit">Edit</button>
            </form>
        </div>
    </div>

    <div>
        <label for="nama">Nama Akun</label><br>
        <div class="kotak-tampilan" id="nama"><?= htmlspecialchars($row['nama']); ?></div>
    </div>
    <div>
        <label for="email">Email</label>
        <div class="kotak-tampilan" id="email"><?= htmlspecialchars($row['email']); ?></div>
    </div>
    <div>
        <label for="password">Kata Sandi</label>
        <div class="kotak-tampilan" id="password"><?= htmlspecialchars($row['password']); ?></div>
    </div>
    <div>
        <label for="nohp">No HP</label>
        <div class="kotak-tampilan" id="hp"><?= htmlspecialchars($row['hp']); ?></div>
    </div>
    <div>
        <label for="alamat">Alamat</label>
        <div class="kotak-tampilan" id="alamat"><?= htmlspecialchars($row['alamat']); ?></div>
    </div>
<?php else: ?>
    <p>Data tidak ditemukan.</p>
<?php endif; ?>
            </section>
        </main>
    </div>

    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>
