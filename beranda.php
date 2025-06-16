<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda | Es Teh AYA</title>
    <link rel="stylesheet" href="beranda.css">
    <link rel="icon" href="gambar/LogoTeh.png" sizes="20x20" />

    <style>
        .nav-horizontal {
    display: flex;
    gap: 3px;
    list-style: none;
    padding: 0;
    margin: 0;
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
            <h1>Es Teh AYA</h1>
            <p>Es Teh AYA adalah minuman teh yang disajikan dalam keadaan dingin, dengan tambahan es batu dan gula asli. <br> 
            Kami memiliki varian es teh yang dipadukan dengan berbagai rasa yang unik.</p>

              
            <div class="menu-grid">
                <div>
                    <img src="gambar/promo1.jpeg" alt="Promo" class="item1">
                </div>
            </div>

            <div class="menu-grid">
                <div>
                    <img src="gambar/promo2.jpeg" alt="Promo" class="item2">
                </div>
            </div>

        </main>
    </div>
    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>