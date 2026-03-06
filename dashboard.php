<?php
// dashboard.php
session_start();
require_once 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// Fetch user bookings with hotel details
$stmt = $pdo->prepare("
    SELECT b.*, h.name as hotel_name, h.location, h.image 
    FROM bookings b 
    JOIN hotels h ON b.hotel_id = h.id 
    WHERE b.user_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | Hotels.com Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">HOTEL<span>S</span></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="hotels.php">Hotels</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
 
    <div class="container">
        <div style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <p style="color: var(--primary); font-weight: 600;">Welcome back, <?php echo $_SESSION['user_name']; ?>!</p>
                <h1 style="font-size: 3rem;">Your Bookings</h1>
            </div>
            <p style="color: var(--text-secondary);"><?php echo count($bookings); ?> reservations</p>
        </div>
 
        <?php if (empty($bookings)): ?>
            <div style="text-align: center; padding: 5rem 0; background: var(--card-bg); border-radius: 24px; border: 1px dashed var(--border-color);">
                <h3 style="color: var(--text-secondary);">You haven't made any bookings yet.</h3>
                <a href="index.php" class="btn btn-primary" style="margin-top: 2rem;">Explore Hotels</a>
            </div>
        <?php else: ?>
            <div class="booking-list">
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-item">
                        <div style="display: flex; gap: 2rem; align-items: center;">
                            <img src="<?php echo $booking['image']; ?>" style="width: 120px; height: 120px; border-radius: 12px; object-fit: cover;">
                            <div>
                                <h3 style="font-size: 1.4rem; margin-bottom: 0.3rem;"><?php echo $booking['hotel_name']; ?></h3>
                                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.8rem;"><?php echo $booking['location']; ?></p>
                                <p style="font-size: 0.95rem;">
                                    <span style="color: var(--text-secondary);">Dates:</span> <?php echo $booking['check_in']; ?> → <?php echo $booking['check_out']; ?>
                                </p>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <p style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">$<?php echo $booking['total_price']; ?></p>
                            <span class="status-badge"><?php echo $booking['status']; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
 
