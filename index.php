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

            if ($level === 'admin') {
                $_SESSION['admin'] = $data['id'];
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background decoration */
        body::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }

        body::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -75px;
            right: -75px;
        }

        .login-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 900px;
            max-width: 90vw;
            min-height: 500px;
            display: flex;
            position: relative;
            z-index: 1;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            color: white;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: 20px;
            right: 20px;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }

        .illustration {
            width: 100%;
            max-width: 300px;
            margin: 0 auto 30px;
            position: relative;
            z-index: 2;
        }

        .illustration svg {
            width: 100%;
            height: auto;
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .login-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.5;
        }

        .login-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 40px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 18px 50px 18px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #ff6b6b;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
        }

        .form-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        .btn-login {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #ff6b6b;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .copyright {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .copyright a {
            color: #ff6b6b;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 95vw;
                max-height: 90vh;
            }

            .login-left {
                padding: 40px 30px;
            }

            .login-right {
                padding: 40px 30px;
            }

            .login-title {
                font-size: 2rem;
            }

            .illustration {
                max-width: 200px;
            }
        }

        /* Loading animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin {
            to { transform: translateY(-50%) rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="illustration">
                <svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background elements -->
                    <circle cx="320" cy="60" r="15" fill="rgba(255,255,255,0.2)"/>
                    <circle cx="50" cy="250" r="20" fill="rgba(255,255,255,0.1)"/>
                    <rect x="30" y="40" width="8" height="8" rx="2" fill="rgba(255,255,255,0.3)"/>
                    <rect x="350" y="200" width="6" height="6" rx="1" fill="rgba(255,255,255,0.2)"/>
                    
                    <!-- Desk -->
                    <ellipse cx="200" cy="280" rx="150" ry="15" fill="rgba(255,255,255,0.1)"/>
                    <rect x="50" y="200" width="300" height="80" rx="8" fill="rgba(255,255,255,0.9)"/>
                    
                    <!-- Computer screen -->
                    <rect x="120" y="120" width="160" height="90" rx="8" fill="#2c3e50"/>
                    <rect x="130" y="130" width="140" height="70" rx="4" fill="#ecf0f1"/>
                    <rect x="135" y="135" width="130" height="3" rx="1" fill="#bdc3c7"/>
                    <rect x="135" y="145" width="100" height="3" rx="1" fill="#bdc3c7"/>
                    <rect x="135" y="155" width="120" height="3" rx="1" fill="#bdc3c7"/>
                    <rect x="135" y="165" width="90" height="3" rx="1" fill="#bdc3c7"/>
                    
                    <!-- Computer stand -->
                    <rect x="190" y="210" width="20" height="20" rx="2" fill="rgba(255,255,255,0.8)"/>
                    <rect x="170" y="225" width="60" height="8" rx="4" fill="rgba(255,255,255,0.7)"/>
                    
                    <!-- Person 1 (sitting) -->
                    <ellipse cx="80" cy="190" rx="25" ry="35" fill="#3498db"/>
                    <circle cx="80" cy="140" r="20" fill="#f4c2a1"/>
                    <path d="M65 135 Q80 125 95 135 Q95 145 80 150 Q65 145 65 135" fill="#8b4513"/>
                    <circle cx="75" cy="142" r="2" fill="#2c3e50"/>
                    <circle cx="85" cy="142" r="2" fill="#2c3e50"/>
                    <path d="M75 148 Q80 152 85 148" stroke="#2c3e50" stroke-width="1" fill="none"/>
                    
                    <!-- Person 2 (standing) -->
                    <ellipse cx="280" cy="160" rx="20" ry="40" fill="#e74c3c"/>
                    <circle cx="280" cy="110" r="18" fill="#f4c2a1"/>
                    <path d="M265 105 Q280 95 295 105 Q295 115 280 120 Q265 115 265 105" fill="#2c3e50"/>
                    <circle cx="275" cy="112" r="2" fill="#2c3e50"/>
                    <circle cx="285" cy="112" r="2" fill="#2c3e50"/>
                    <path d="M275 118 Q280 122 285 118" stroke="#2c3e50" stroke-width="1" fill="none"/>
                    
                    <!-- Arms -->
                    <ellipse cx="260" cy="140" rx="8" ry="20" fill="#f4c2a1" transform="rotate(-20 260 140)"/>
                    <ellipse cx="300" cy="140" rx="8" ry="20" fill="#f4c2a1" transform="rotate(20 300 140)"/>
                    
                    <!-- Documents/papers floating -->
                    <rect x="320" y="100" width="15" height="20" rx="2" fill="rgba(255,255,255,0.9)" transform="rotate(15 327 110)"/>
                    <rect x="340" y="120" width="12" height="16" rx="2" fill="rgba(255,255,255,0.8)" transform="rotate(-10 346 128)"/>
                    
                    <!-- Plants -->
                    <ellipse cx="40" cy="180" rx="8" ry="15" fill="#27ae60"/>
                    <rect x="35" y="190" width="10" height="15" rx="2" fill="rgba(255,255,255,0.8)"/>
                    
                    <ellipse cx="360" cy="170" rx="6" ry="12" fill="#27ae60"/>
                    <rect x="357" y="178" width="6" height="10" rx="1" fill="rgba(255,255,255,0.8)"/>
                </svg>
            </div>
            <h1 class="login-title">Sign In</h1>
            <p class="login-subtitle">Sign in to continue to our application.</p>
        </div>
        
        <div class="login-right">
            <form role="form" action="" method="post" id="loginForm">
                <h2 class="form-title">Welcome Back</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus />
                    <i class="fas fa-user form-icon"></i>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required />
                    <i class="fas fa-lock form-icon"></i>
                </div>
                
                <button type="submit" name="login" class="btn-login" id="loginBtn">
                    Sign In
                    <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                </button>
                
                
            </form>
        </div>
    </div>

    <div class="copyright">
        <p style="color: white">Copyright © Inventory System 2025  
            <a	href='https://dkriuk.com/' title='Dkriuk' target='_blank'>Dkriuk Bekasi</a>
        </p>
    </div>

    <script>
        // Add loading animation on form submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.innerHTML = 'Signing In...';
        });

        // Add focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
