<?php
// login.php
session_start();
require_once 'db.php';
 
$error = '';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
 
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hotels.com Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">HOTEL<span>S</span></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="hotels.php">Hotels</a>
            <a href="signup.php">Signup</a>
        </nav>
    </header>
 
    <div class="auth-container">
        <h2 class="auth-title">Welcome Back</h2>
        <?php if ($error): ?>
            <p style="color: var(--secondary); margin-bottom: 1rem;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group" style="margin-bottom: 1.5rem;">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="input-group" style="margin-bottom: 2rem;">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary);">
            Don't have an account? <a href="signup.php" style="color: var(--primary); text-decoration: none;">Sign Up</a>
        </p>
    </div>
</body>
</html>
 
