<?php
require_once 'db_con.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_login'])) { header('Location: login.php'); exit; }

$__u = $_SESSION['user_login'];
$stmt = mysqli_prepare($db_con, "SELECT `role` FROM `users` WHERE `username` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $__u);
mysqli_stmt_execute($stmt);
$__res = mysqli_stmt_get_result($stmt);
$__row = $__res ? mysqli_fetch_assoc($__res) : null;
if (!$__row || $__row['role'] !== 'admin') { header('Location: ../profile.php'); exit; }

if (isset($_GET['id'])) {
    $player_id = base64_decode($_GET['id']);
    $stmt = mysqli_prepare($db_con, "SELECT p.*, s.name as sport_name FROM `players` p JOIN `sports` s ON p.sport_id = s.id WHERE p.id = ?");
    mysqli_stmt_bind_param($stmt, "i", $player_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $player = mysqli_fetch_assoc($result);
} else {
    header('Location: index.php?page=all-players');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player ID Card</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/id-card.css">
</head>
<body>
    <div class="container">
        <div class="id-card">
            <div class="id-card-header">
                <h2>Player ID Card</h2>
            </div>
            <div class="id-card-body">
                <div class="id-card-photo">
                    <img src="images/<?php echo $player['photo']; ?>" alt="Player Photo">
                </div>
                <div class="id-card-info">
                    <p><strong>Name:</strong> <?php echo ucwords($player['full_name']); ?></p>
                    <p><strong>Player ID:</strong> <?php echo $player['player_id']; ?></p>
                    <p><strong>Sport:</strong> <?php echo ucwords($player['sport_name']); ?></p>
                </div>
            </div>
            <div class="id-card-footer">
                <p>Barangay Sports Registration System</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <button class="btn btn-primary" onclick="window.print()">Print ID Card</button>
            <a href="index.php?page=all-players" class="btn btn-secondary">Back to All Players</a>
        </div>
    </div>
</body>
</html>
