<?php
require_once 'admin/db_con.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Security check: ensure user is logged in and is a player
if (!isset($_SESSION['user_login'])) {
    header('Location: admin/login.php');
    exit;
}

$username = $_SESSION['user_login'];
$res = mysqli_query($db_con, "SELECT * FROM `users` WHERE `username` = '".mysqli_real_escape_string($db_con, $username)."' AND `role` = 'player' AND `status` = 'approved' LIMIT 1");
$user = $res ? mysqli_fetch_assoc($res) : null;

if (!$user) {
    // If user is not an approved player, redirect to profile page which will show appropriate status
    header('Location: profile.php');
    exit;
}

$page = $_GET['page'] ?? 'main';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Player Dashboard</title>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-end mb-3">
        <a class="btn btn-secondary mr-2" href="profile.php">Back to Profile</a>
        <a class="btn btn-danger" href="admin/logout.php">Logout</a>
    </div>
    <h2 class="mb-4">Player Dashboard</h2>

    <div class="card">
        <div class="card-body">
            <?php if ($page === 'view-team'): ?>
                <h4>My Team</h4>
                <p>This is where team information will be displayed.</p>
            <?php elseif ($page === 'view-schedule'): ?>
                <h4>My Schedule</h4>
                <p>This is where the game and practice schedule will be displayed.</p>
            <?php else: ?>
                <p>Welcome to your dashboard, <?php echo htmlspecialchars($user['full_name']); ?>!</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
