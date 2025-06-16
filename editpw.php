<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "koneksi.php";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password_baru'];
    $confirm_password = $_POST['konfirmasi_password'];

    if ($new_password !== $confirm_password) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        if (isset($_SESSION['email_reset'])) {
            $email = $_SESSION['email_reset'];
            $password = $new_password; 

            $conn = new mysqli("sql210.infinityfree.com", "if0_39243975", "yQHo1wIY0uwxsch", "if0_39243975_db_esteh");

            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("UPDATE pembeli SET password=? WHERE email=?");
            if (!$stmt) {
                die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $stmt->bind_param("ss", $password, $email);

            if ($stmt->execute()) {
                $success = "Password berhasil diupdate!";
                unset($_SESSION['email_reset']);
                session_destroy();
                header("Location: login.php"); 
                exit;
            } else {
                $error = "Gagal memperbarui password: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            $error = "Session email tidak ditemukan. Silakan ulangi proses reset.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Es Teh AYA</title>
    <link rel="icon" href="gambar/LogoTeh.png">
    <link rel="stylesheet" href="editpw.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .container {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            color: #4c693e;
            font-family: 'Times New Roman', Times, serif;
        }

        label {
            font-weight: bold;
            color: #4c693e;
            display: block;
            margin-top: 15px;
        }

        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            margin-top: 20px;
            background: #87c346;
            color: white;
            font-weight: bold;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #76a832;
        }

        .error, .success {
            text-align: center;
            margin-top: 10px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        footer {
            background-color: #98a68e;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            position: relative;
        }

        @media (max-width: 768px) {
            .login-box {
                width: 90%;
                margin-top: 30px;
            }
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <div class="login-box">
                <h2>Reset Password</h2>

                <?php
                if (!empty($error)) echo "<div class='error'>$error</div>";
                if (!empty($success)) echo "<div class='success'>$success</div>";
                ?>

                <form method="POST">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" name="password_baru" id="password_baru" required>

                    <label for="konfirmasi_password">Konfirmasi Password</label>
                    <input type="password" name="konfirmasi_password" id="konfirmasi_password" required>

                    <input type="submit" value="Simpan Password">
                </form>
            </div>
        </main>

        <footer>
            <i>copyright by @EsTeh AYA</i>
        </footer>
    </div>
</body>
</html>
