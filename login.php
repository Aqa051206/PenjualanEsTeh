<?php
session_start(); 
require_once "koneksi.php";
require_once "query/pembeli.php";

$database = new Database();
$conn = $database->connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUser = $_POST['nama'];
    $inputPass = $_POST['password'];

    $stmt = $conn->prepare("SELECT idPembeli, nama, password, role FROM pembeli WHERE nama = :nama");
    $stmt->execute(['nama' => $inputUser]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $inputPass === $user['password']) {
        session_regenerate_id(true);
        $_SESSION['idPembeli'] = $user['idPembeli'];  
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];            
        header("Location:beranda.php");
        exit;
    } else {
        $error = "Nama Akun atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Es Teh AYA</title>
    <link rel="icon" href="gambar/LogoTeh.png">
    <link rel="stylesheet" href="login.css">
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
                    <li><a href="login.php" class="active">Login</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <div class="login-box">
                <h2>Masuk ke Es Teh AYA</h2>
                <?php if (!empty($error)): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <label for="nama">Nama Akun</label>
                    <input type="text" name="nama" id="nama" required>

                    <label for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" required>

                    <input type="submit" value="Masuk">
                </form>
                <div class="bawah">
                    <a href="lupapassword.php">Lupa Sandi?</a> | 
                    <a href="daftar.php">Daftar</a>
                </div>
            </div>
        </main>
    </div>
    <footer><i>copyright by @EsTeh AYA</i></footer>
</body>
</html>
