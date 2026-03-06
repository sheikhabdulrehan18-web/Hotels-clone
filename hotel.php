<?php
// hotel.php
session_start();
require_once 'db.php';
 
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
 
$stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->execute([$id]);
$hotel = $stmt->fetch();
 
if (!$hotel) {
    header("Location: hotels.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hotel['name']; ?> | Hotels.com Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">HOTEL<span>S</span></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="hotels.php">Hotels</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
 
    <div class="container">
        <div class="detail-grid">
            <div>
                <div class="detail-image">
                    <img src="<?php echo $hotel['image']; ?>" alt="<?php echo $hotel['name']; ?>">
                </div>
                <div style="margin-top: 2rem;">
                    <p class="hotel-location" style="font-size: 1.1rem;"><?php echo $hotel['location']; ?></p>
                    <h1 style="font-size: 3rem; margin-bottom: 1.5rem;"><?php echo $hotel['name']; ?></h1>
                    <p style="color: var(--text-secondary); font-size: 1.1rem; line-height: 1.8;">
                        <?php echo $hotel['description']; ?>
                        <br><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                </div>
            </div>
 
            <aside>
                <div class="booking-card">
                    <h3 style="margin-bottom: 1.5rem; font-size: 1.5rem;">Reserve your stay</h3>
                    <p class="hotel-price" style="font-size: 2rem; margin-bottom: 2rem;">$<?php echo $hotel['price_per_night']; ?> <span style="font-size: 1rem;">/ night</span></p>
 
                    <form action="book.php" method="POST">
                        <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
                        <div class="input-group" style="margin-bottom: 1rem;">
                            <label>Check-in</label>
                            <input type="date" name="check_in" value="<?php echo $check_in; ?>" required>
                        </div>
                        <div class="input-group" style="margin-bottom: 2rem;">
                            <label>Check-out</label>
                            <input type="date" name="check_out" value="<?php echo $check_out; ?>" required>
                        </div>
 
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Confirm Reservation</button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary" style="width: 100%; text-align: center;">Login to Book</a>
                        <?php endif; ?>
                    </form>
 
                    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">
                        No charge until you check out.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>
 
