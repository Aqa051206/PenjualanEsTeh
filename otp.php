<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_otp = htmlspecialchars($_POST['otp']);

    $otp_asli = $_SESSION['otp'] ?? '123456';

    if ($input_otp === $otp_asli) {
        header("Location: editpw.php");
        exit;
    } else {
        $error = "Kode OTP salah atau sudah kadaluarsa.";
    }
}

if (!isset($_SESSION['otp'])) {
    $_SESSION['otp'] = '123456'; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP | Es Teh AYA</title>
    <link rel="icon" href="gambar/LogoTeh.png">
    <link rel="stylesheet" href="otp.css">
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

        .login-box h2 {
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            margin-bottom: 20px;
            color: #4c693e;
        }

        .login-box form {
            display: flex;
            flex-direction: column;
        }

        .login-box label {
            margin-top: 10px;
            font-weight: bold;
            color: #4c693e;
        }

        .login-box input[type="text"] {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .login-box input[type="submit"] {
            margin-top: 20px;
            background-color: #87c346;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .login-box input[type="submit"]:hover {
            background-color: #76a832;
        }

        .error {
            color: red;
            font-size: 0.9em;
            text-align: center;
        }

        footer {
            background-color: #98a68e !important;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            opacity: 1;
            z-index: 10;
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
                <h2>Verifikasi Kode OTP</h2>

                <?php if (!empty($error)): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <label for="otp">Kode OTP</label>
                    <input type="text" name="otp" id="otp" maxlength="6" required placeholder="Masukkan 6 digit kode">

                    <input type="submit" value="Verifikasi">
                </form>
            </div>
        </main>

        <footer>
            <i>copyright by @EsTeh AYA</i>
        </footer>
    </div>
</body>
</html>
