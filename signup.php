<?php
// signup.php
session_start();
require_once 'db.php';
 
$error = '';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
 
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Email already registered.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Hotels.com Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">HOTEL<span>S</span></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="hotels.php">Hotels</a>
            <a href="login.php">Login</a>
        </nav>
    </header>
 
    <div class="auth-container">
        <h2 class="auth-title">Create Account</h2>
        <?php if ($error): ?>
            <p style="color: var(--secondary); margin-bottom: 1rem;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group" style="margin-bottom: 1.5rem;">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="John Doe">
            </div>
            <div class="input-group" style="margin-bottom: 1.5rem;">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="input-group" style="margin-bottom: 2rem;">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-secondary);">
            Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none;">Log In</a>
        </p>
    </div>
</body>
</html>
 
