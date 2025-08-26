<?php
require_once 'admin/db_con.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_login'])) {
    header('Location: admin/login.php');
    exit;
}
$username = $_SESSION['user_login'];
$stmt = mysqli_prepare($db_con, "SELECT * FROM `users` WHERE `username` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = $res ? mysqli_fetch_assoc($res) : null;
if (!$user) {
    session_destroy();
    header('Location: admin/login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>My Profile</title>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-end mb-3">
        <a class="btn btn-secondary mr-2" href="index.php">Home</a>
        <a class="btn btn-danger" href="admin/logout.php">Logout</a>
    </div>
    <h2 class="mb-4">My Profile</h2>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user['role'])); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($user['status'])); ?></p>
                    <p><strong>Eligibility Document:</strong>
                        <?php if (!empty($user['eligibility_document'])) { ?>
                            <a href="admin/view_document.php?doc=<?php echo urlencode($user['eligibility_document']); ?>" target="_blank">View Document</a>
                        <?php } else { ?>
                            N/A
                        <?php } ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($user['role'] === 'default') { ?>
        <div class="alert alert-info">You have not selected a role yet. Please request a role to proceed.</div>
        <a href="request-role.php" class="btn btn-primary">Request Role</a>
    <?php } elseif ($user['status'] !== 'approved') { ?>
        <div class="alert alert-warning">Your application is currently pending approval. You can view your profile and status here. Other functionality is disabled until approval.</div>
    <?php } else { ?>
        <div class="card mt-4">
            <div class="card-header">
                <h4><?php echo htmlspecialchars(ucfirst($user['role'])); ?> Dashboard</h4>
            </div>
            <div class="card-body">
                <?php if ($user['role'] === 'player') { ?>
                    <p>Welcome, Player! Your account is approved. Here are your available actions:</p>
                    <a href="player-dashboard.php?page=view-team" class="btn btn-primary">View My Team</a>
                    <a href="player-dashboard.php?page=view-schedule" class="btn btn-info">View Schedule</a>
                <?php } elseif ($user['role'] === 'coach') { ?>
                    <p>Welcome, Coach! Your account is approved. Here are your available actions:</p>
                    <a href="coach-dashboard.php?page=manage-team" class="btn btn-primary">Manage My Team</a>
                    <a href="coach-dashboard.php?page=set-schedule" class="btn btn-info">Set Schedule</a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>