<?php
if (strpos($_SERVER['REQUEST_URI'], "pages")) {
    exit(header("Location:../index.php"));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .info {
            font-size: 13px;
            text-align: center;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            font-size: 13px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: none;
            margin-top: 5px;
            outline: none;
            font-size: 14px;
        }

        .input-group input:focus {
            box-shadow: 0 0 12px rgba(0, 242, 254, 0.8);
        }

        .captcha-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .captcha-container img {
            border-radius: 10px;
            max-height: 45px;
            /* batas atas saja */
            width: auto;
            /* biar proporsional */
        }

        .refresh-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer;
        }

        .refresh-btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: #00f2fe;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background: #4facfe;
            color: #fff;
        }

        .error {
            background: rgba(255, 0, 0, 0.25);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 13px;
            text-align: center;
        }

        /* MOBILE */
        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
                border-radius: 15px;
            }

            .captcha-container {
                flex-direction: column;
                align-items: stretch;
            }

            .captcha-container img {
                width: 100%;
            }
        }
    </style>
    <link rel="icon" href="/images/logo.png">

    <script>
        function refreshCaptcha() {
            document.getElementById('captcha-img').src = 'pages/captcha.php?' + Date.now();
        }
    </script>
</head>

<body>

    <div class="login-container">
        <h2>Login E-Pasien</h2>

        <div class="info">
            Gunakan nomor rekam medis & password Anda
        </div>

        <?php
        $BtnLogin = isset($_POST['BtnLogin']) ? $_POST['BtnLogin'] : NULL;

        function tampilForm($pesan = "")
        {
            echo $pesan;
            echo '<form method="post" action="">
    
    <div class="input-group">
        <label>No Rekam Medis</label>
        <input type="password" name="norme" pattern="[A-Z0-9-]{1,65}" required placeholder="Nomor RM" autocomplete="off">
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="passworde" required placeholder="Password" autocomplete="off">
    </div>

    <div class="input-group">
        <label>Captcha</label>
        <div class="captcha-container">
            <img id="captcha-img" src="pages/captcha.php">
            <button type="button" class="refresh-btn" onclick="refreshCaptcha()">↻</button>
            <input type="text" name="inputcaptcha" pattern="[0-9]{1,6}" required placeholder="Masukkan captcha">
        </div>
    </div>

    <button type="submit" name="BtnLogin">Login</button>
    </form>';
        }

        if (isset($BtnLogin)) {

            if (@$_SESSION["Capcay"] != getOne2("select aes_encrypt(" . cleankar($_POST["inputcaptcha"]) . ",'windi')")) {
                tampilForm('<div class="error">Captcha tidak sesuai!</div>');
            } else {
                unset($_SESSION['Capcay']);

                $usere      = cleankar($_POST['norme']);
                $passworde  = validTeks($_POST['passworde']);

                if (strlen($usere) > 30) {
                    header('Location: https://www.google.com');
                } else {
                    if (getOne2("select count(*) from personal_pasien where md5(no_rkm_medis)=md5('$usere') and password=aes_encrypt('$passworde','windi')") > 0) {
                        $_SESSION["ses_pasien"] = encrypt_decrypt($usere, "e");
                        exit(header("Location:index.php"));
                    } else {
                        tampilForm('<div class="error">Login gagal! Data salah.</div>');
                    }
                }
            }
        } else {
            tampilForm();
        }
        ?>

    </div>

</body>

</html>