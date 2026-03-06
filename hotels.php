<?php
// hotels.php
session_start();
require_once 'db.php';
 
$location = isset($_GET['location']) ? $_GET['location'] : '';
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
 
// Base query
$query = "SELECT * FROM hotels";
$params = [];
 
if (!empty($location)) {
    $query .= " WHERE location LIKE ?";
    $params[] = '%' . $location . '%';
}
 
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$hotels = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Hotels.com Clone</title>
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
        <div style="margin-bottom: 3rem;">
            <h1>Available Stays <?php echo !empty($location) ? "in $location" : ""; ?></h1>
            <p style="color: var(--text-secondary);"><?php echo count($hotels); ?> properties found</p>
        </div>
 
        <?php if (empty($hotels)): ?>
            <div style="text-align: center; padding: 5rem 0;">
                <h3 style="font-size: 2rem; color: var(--text-secondary);">No hotels found matching your search.</h3>
                <a href="index.php" class="btn btn-primary" style="margin-top: 2rem;">Try another search</a>
            </div>
        <?php else: ?>
            <div class="hotel-grid">
                <?php foreach ($hotels as $hotel): ?>
                    <div class="hotel-card">
                        <div class="hotel-image">
                            <img src="<?php echo $hotel['image']; ?>" alt="<?php echo $hotel['name']; ?>">
                        </div>
                        <div class="hotel-content">
                            <p class="hotel-location"><?php echo $hotel['location']; ?></p>
                            <h3 class="hotel-name"><?php echo $hotel['name']; ?></h3>
                            <p class="hotel-price">$<?php echo $hotel['price_per_night']; ?> <span>/ night</span></p>
                            <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 1rem 0; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?php echo $hotel['description']; ?>
                            </p>
                            <a href="hotel.php?id=<?php echo $hotel['id']; ?>&check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out; ?>" class="btn btn-primary" style="width: 100%; text-align: center;">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
 
