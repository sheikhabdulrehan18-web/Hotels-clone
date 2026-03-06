<?php
// index.php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels.com Clone | Find Your Perfect Stay</title>
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
                <a href="signup.php" class="btn btn-outline" style="padding: 0.5rem 1.2rem;">Join</a>
            <?php endif; ?>
        </nav>
    </header>
 
    <div class="container hero">
        <h1>Find your next <br><span style="color: var(--primary);">Extraordinary</span> stay.</h1>
        <p style="color: var(--text-secondary); font-size: 1.2rem; margin-top: 1rem;">Discover amazing deals on hotels, resorts, and vacation homes.</p>
 
        <form action="hotels.php" method="GET" class="search-box">
            <div class="input-group">
                <label>Where to?</label>
                <input type="text" name="location" placeholder="e.g. Paris, Tokyo..." required>
            </div>
            <div class="input-group">
                <label>Check-in</label>
                <input type="date" name="check_in" required>
            </div>
            <div class="input-group">
                <label>Check-out</label>
                <input type="date" name="check_out" required>
            </div>
            <button type="submit" class="btn btn-primary">Search Hotels</button>
        </form>
    </div>
 
    <div class="container">
        <h2 style="font-size: 2.5rem; margin-bottom: 2rem;">Recommended for you</h2>
        <div class="hotel-grid">
            <?php
            // Fetch 3 random hotels for recommendations
            $stmt = $pdo->query("SELECT * FROM hotels ORDER BY RAND() LIMIT 3");
            while ($hotel = $stmt->fetch()):
            ?>
                <div class="hotel-card">
                    <div class="hotel-image">
                        <img src="<?php echo $hotel['image']; ?>" alt="<?php echo $hotel['name']; ?>">
                    </div>
                    <div class="hotel-content">
                        <p class="hotel-location"><?php echo $hotel['location']; ?></p>
                        <h3 class="hotel-name"><?php echo $hotel['name']; ?></h3>
                        <p class="hotel-price">$<?php echo $hotel['price_per_night']; ?> <span>/ night</span></p>
                        <a href="hotel.php?id=<?php echo $hotel['id']; ?>" class="btn btn-outline" style="width: 100%; margin-top: 1.5rem; text-align: center;">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
 
    <footer style="text-align: center; padding: 4rem 0; color: var(--text-secondary); border-top: 1px solid var(--border-color);">
        <p>&copy; 2024 Hotels.com Clone Project. Developed with ❤️ Using PHP & CSS.</p>
    </footer>
</body>
</html>
 
