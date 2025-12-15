<?php
require_once 'config.php';

$error = '';
$success = '';

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Mật khẩu không đúng!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }
}

// Handle Register
if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Mật khẩu nhập lại không khớp!";
    } else {
        $check = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email'");
        if ($check->num_rows > 0) {
            $error = "Username hoặc Email đã tồn tại!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $hashed_password, $fullname, $email);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công! Vui lòng đăng nhập.";
            } else {
                $error = "Đã có lỗi xảy ra.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập - Football Shop</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <style>
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/banner.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 450px;
            background: rgba(30, 30, 30, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid #333;
            backdrop-filter: blur(10px);
            margin: auto;
            /* Center vertical and horizontal */
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        /* ... existing styles ... */

        .toggle-link {
            color: var(--secondary-color);
            cursor: pointer;
            text-decoration: underline;
            transition: 0.3s;
        }

        .toggle-link:hover {
            color: #fff;
        }

        .form-control {
            background: #222;
            border: 1px solid #444;
            height: 45px;
            font-size: 1rem;
        }

        .btn {
            height: 45px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1rem;
        }

        /* Ẩn hiện form */
        #register-form {
            display: none;
        }

        /* Home link absolute */
        .home-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 100;
        }

        .home-link:hover {
            color: var(--primary-color);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <a href="index.php" class="home-link"><i class="fas fa-arrow-left"></i> Trang Chủ</a>

    <div class="auth-wrapper">
        <!-- LOGIN FORM -->
        <div id="login-form">
            <div class="auth-header">
                <h2>Đăng Nhập</h2>
                <p>Chào mừng bạn trở lại!</p>
            </div>

            <?php if ($error && isset($_POST['login'])) echo "<div style='background:rgba(255, 68, 68, 0.1); color: #ff4444; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;'>$error</div>"; ?>
            <?php if ($success) echo "<div style='background:rgba(0, 255, 136, 0.1); color: #00ff88; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;'>$success</div>"; ?>

            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" name="login" class="btn" style="width: 100%;">Đăng Nhập</button>
            </form>

            <p class="text-center" style="margin-top: 1.5rem; color: #aaa;">
                Chưa có tài khoản? <span class="toggle-link" onclick="toggleAuth()">Đăng ký ngay</span>
            <div>default user: user/password</div>
            <div>default admin:admin/password</div>
            </p>
        </div>

        <!-- REGISTER FORM -->
        <div id="register-form">
            <div class="auth-header">
                <h2>Đăng Ký</h2>
                <p>Tạo tài khoản mới</p>
            </div>

            <?php if ($error && isset($_POST['register'])) echo "<div style='background:rgba(255, 68, 68, 0.1); color: #ff4444; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;'>$error</div>"; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Họ Tên</label>
                    <input type="text" name="fullname" class="form-control" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="username123" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="btn" style="width: 100%;">Đăng Ký</button>
            </form>

            <p class="text-center" style="margin-top: 1.5rem; color: #aaa;">
                Đã có tài khoản? <span class="toggle-link" onclick="toggleAuth()">Đăng nhập</span>
            </p>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function toggleAuth() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                document.title = 'Đăng Nhập - Football Shop';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                document.title = 'Đăng Ký - Football Shop';
            }
        }

        // Show register form if there was a register error OR if requested via URL
        <?php if ((isset($_POST['register']) && $error) || (isset($_GET['mode']) && $_GET['mode'] == 'register')): ?>
            toggleAuth();
        <?php endif; ?>
    </script>
</body>

</html>