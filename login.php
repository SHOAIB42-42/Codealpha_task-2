<?php
// login.php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) header("Location: admin/index.php");
    else header("Location: tenant/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        setFlash("Welcome back, " . $user['username'] . "!", "success");

        if ($user['role'] === 'admin') header("Location: admin/index.php");
        else header("Location: tenant/dashboard.php");
        exit();
    } else {
        setFlash("Invalid username or password.", "error");
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EstateHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS (Assuming main.css is available or we add toast container here) -->
    <script src="assets/js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($flash = getFlash()): ?>
                showToast("<?php echo $flash['message']; ?>", "<?php echo $flash['type']; ?>");
            <?php endif; ?>
        });
    </script>
    <style>
        :root {
            --primary: #198754;
            --primary-light: #20c997;
            --bg-main: #f4f7f6;
        }
        body {
            background: linear-gradient(135deg, #f4f7f6 0%, #e9ecef 100%);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            background: white;
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .login-header {
            background: var(--primary);
            padding: 3rem 2rem;
            text-align: center;
            color: white;
        }
        .login-body { padding: 3rem 2.5rem; }
        .form-control {
            border: 1px solid #eee;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            background: #fcfcfc;
            transition: all 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.1);
            border-color: var(--primary);
            background: white;
        }
        .btn-login {
            background: var(--primary);
            border: none;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #157347;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
        }
        .logo-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="logo-icon">
            <i class="fas fa-building"></i>
        </div>
        <h3 class="fw-bold mb-1">EstateHub</h3>
        <p class="mb-0 opacity-75">Welcome back! Please login</p>
    </div>
    <div class="login-body">
        <?php if ($error): ?>
            <div class="alert alert-danger border-0 small py-2 mb-4 rounded-3">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="far fa-user"></i></span>
                    <input type="text" name="username" class="form-control border-start-0" placeholder="Enter username" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="far fa-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Remember me</label>
                </div>
                <a href="#" class="small text-primary text-decoration-none">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-login text-white">Sign In <i class="fas fa-arrow-right ms-2"></i></button>
        </form>
        
        <div class="text-center mt-5">
            <p class="small text-muted">Don't have an account? <a href="#" class="text-primary fw-bold text-decoration-none">Contact Admin</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
