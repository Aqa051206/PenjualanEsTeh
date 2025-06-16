<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $otp = strval(rand(100000, 999999));

    $_SESSION['otp'] = $otp;
    $_SESSION['email_reset'] = $email;
    $_SESSION['nama_reset'] = $nama;

    $mail = new PHPMailer(true);
    try {
        
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adityasuryanugraheni@gmail.com';       
        $mail->Password   = 'hlpf tkbt gton gvos';           
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

    
        $mail->setFrom('adityasuryanugraheni@gmail.com', 'Es Teh AYA');
        $mail->addAddress($email, $nama);


        $mail->isHTML(true);
        $mail->Subject = "Kode OTP Verifikasi Es Teh AYA";
        $mail->Body    = "
            Halo <b>$nama</b>,<br><br>
            Kode OTP kamu adalah: <strong>$otp</strong><br><br>
            Jangan bagikan kode ini ke siapa pun.<br><br>
            Salam,<br>Tim Es Teh AYA
        ";

        $mail->send();
        header("Location: otp.php");
        exit;
    } catch (Exception $e) {
        $success = "<span style='color:red;'>Gagal mengirim email OTP. Error: {$mail->ErrorInfo}</span>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password | Es Teh AYA</title>
    <link rel="icon" href="gambar/LogoTeh.png">
    <link rel="stylesheet" href="lupapassword.css">
    <style>
        .form-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 350px;
            margin: auto;
            margin-top: 50px;
        }

        .form-box h2 {
        text-align: center;
        font-family: 'Times New Roman', Times, serif; 
        margin-bottom: 20px;
        color: #4c693e;
        }


        .form-box form {
            display: flex;
            flex-direction: column;
        }

        .form-box label {
            margin-top: 10px;
            font-weight: bold;
            color: #4c693e;
        }

        .form-box input[type="text"],
        .form-box input[type="email"] {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-box input[type="submit"] {
            margin-top: 20px;
            background-color: #87c346;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-box input[type="submit"]:hover {
            background-color: #76a832;
        }

        .form-box .bawah {
            text-align: center;
            margin-top: 15px;
        }

        .form-box .bawah a {
            text-decoration: none;
            color: #00796b;
            margin: 5px;
            display: inline-block;
        }

        .message {
            color: green;
            font-size: 0.9em;
            text-align: center;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .form-box {
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
            <div class="form-box">
                <h2>Lupa Password</h2>

                <?php if (!empty($success)): ?>
                    <div class="message"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST">
                    <label for="nama">Nama Akun</label>
                    <input type="text" name="nama" id="nama" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>

                    <input type="submit" value="Kirim Permintaan">
                </form>

                <div class="bawah">
                    <a href="login.php">Login</a> | 
                    <a href="daftar.php">Daftar</a>
                </div>
            </div>
        </main>
    </div>
    <footer>
            <i>copyright by @EsTeh AYA</i>
        </footer>
</body>
</html>
