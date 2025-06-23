<?php
session_start();
$koneksi = new mysqli("localhost","pora5278_fahmi","Au1b839@@","pora5278_inventrizki");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Escape input to prevent SQL injection
    $username = $koneksi->real_escape_string($username);

    // Query user by username
    $sql = $koneksi->query("SELECT * FROM users WHERE username='$username' LIMIT 1");
    $data = $sql->fetch_assoc();

    if ($data) {
        $hash_db = $data['password'];
        $valid = false;

        // If password in DB is bcrypt/hash format
        if (substr($hash_db, 0, 4) === '$2y$') {
            if (password_verify($password, $hash_db)) {
                $valid = true;
            }
        } else {
            // If password in DB is MD5
            if (md5($password) === $hash_db) {
                $valid = true;
            }
        }

        if ($valid) {
            // Get user level from DB
            $level = $data['level'];

            if ($level === 'superadmin') {
                $_SESSION['superadmin'] = $data['id'];
                header("Location: index3.php");
                exit;
            } elseif ($level === 'petugas') {
                $_SESSION['petugas'] = $data['id'];
                header("Location: index2.php");
                exit;
            } else {
                $error = "Level user tidak dikenali.";
            }
        } else {
            $error = "Login gagal. Password salah.";
        }
    } else {
        $error = "Login gagal. Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="img/dkriuk.jpg">
    <title>Login Sistem</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
              background: url(img/bg.jpg) no-repeat fixed;
              background-size: 100% 100%;
            }
        .row { margin:100px auto; width:300px; text-align:center; }
        .login { background-color:#FFFFFF; padding:20px; margin-top:20px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="center">
                <div class="login">
                    <form role="form" action="" method="post">
                        <h2>Log In</h2>
                        <br>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus />
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password" required />
                        </div>
                        <div class="form-group">
                            <input type="submit" name="login" class="btn btn-primary btn-block" value="Log in" />
                        </div>
                        <br>
                    </form>
                    <br>
                    <center>
                        <p>Copyright © Inventory System 2025  
                            <a href='https://dkriuk.com/' title='Dkriuk' target='_blank'>Dkriuk Bekasi</a>
                        </p>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
