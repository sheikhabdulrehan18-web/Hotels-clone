<?php
// book.php
session_start();
require_once 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$success = false;
$booking_details = null;
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hotel_id'])) {
    $user_id = $_SESSION['user_id'];
    $hotel_id = $_POST['hotel_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
 
    // Fetch hotel price
    $stmt = $pdo->prepare("SELECT name, price_per_night FROM hotels WHERE id = ?");
    $stmt->execute([$hotel_id]);
    $hotel = $stmt->fetch();
 
    if ($hotel) {
        $date1 = new DateTime($check_in);
        $date2 = new DateTime($check_out);
        $diff = $date1->diff($date2);
        $days = $diff->days;
        if ($days <= 0) $days = 1; // Minimum 1 night
 
        $total_price = $days * $hotel['price_per_night'];
 
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, hotel_id, check_in, check_out, total_price) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $hotel_id, $check_in, $check_out, $total_price])) {
            $success = true;
            $booking_details = [
                'hotel_name' => $hotel['name'],
                'check_in' => $check_in,
                'check_out' => $check_out,
                'total_price' => $total_price,
                'days' => $days
            ];
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation | Hotels.com Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">HOTEL<span>S</span></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="hotels.php">Hotels</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
 
    <div class="container" style="max-width: 800px; margin: 0 auto; text-align: center;">
        <?php if ($success): ?>
            <div style="background: var(--card-bg); padding: 4rem; border-radius: 30px; border: 1px solid var(--primary); animation: slideUp 0.8s ease;">
                <div style="font-size: 5rem; color: #22c55e; margin-bottom: 2rem;">✓</div>
                <h1 style="font-size: 3rem; margin-bottom: 1rem;">Booking Confirmed!</h1>
                <p style="color: var(--text-secondary); font-size: 1.2rem; margin-bottom: 3rem;">Pack your bags! Your reservation at <strong><?php echo $booking_details['hotel_name']; ?></strong> is all set.</p>
 
                <div style="text-align: left; background: rgba(15, 23, 42, 0.4); padding: 2rem; border-radius: 20px; border: 1px solid var(--border-color); margin-bottom: 3rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span>Stay Duration</span>
                        <span><?php echo $booking_details['days']; ?> nights</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span>Dates</span>
                        <span><?php echo $booking_details['check_in']; ?> to <?php echo $booking_details['check_out']; ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-weight: 700; color: var(--primary); font-size: 1.2rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                        <span>Total Paid</span>
                        <span>$<?php echo number_format($booking_details['total_price'], 2); ?></span>
                    </div>
                </div>
 
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                    <a href="index.php" class="btn btn-outline">Back to Home</a>
                </div>
            </div>
        <?php else: ?>
            <div style="padding: 5rem 0;">
                <h2 style="color: var(--secondary);">Something went wrong with your booking.</h2>
                <a href="index.php" class="btn btn-primary" style="margin-top: 2rem;">Try Again</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
 
