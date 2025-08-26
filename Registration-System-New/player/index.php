<?php
require_once '../admin/db_con.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_login'])) {
    header('Location: ../admin/login.php');
    exit;
}

$username = $_SESSION['user_login'];
$stmt = mysqli_prepare($db_con, "SELECT * FROM `users` WHERE `username` = ? AND `role` = 'player' AND `status` = 'approved' LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = $res ? mysqli_fetch_assoc($res) : null;

if (!$user) {
    header('Location: ../profile.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesome.min.css">
    <link rel="stylesheet" href="../css/solid.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Player Dashboard</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php"><i class="fas fa-chart-line fa-3x"></i></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse collapse justify-content-end" id="navbarSupportedContent">
    <ul class="nav navbar-nav ">
      <li class="nav-item"><a class="nav-link" href="index.php?page=user-profile"><i class="fa fa-user"></i>
          Welcome <?php echo ucwords($user['full_name']); ?>!</a></li>
      <li class="nav-item"><a class="nav-link" href="../admin/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
    </ul>
  </div>
</nav>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="index.php?page=dashboard" class="list-group-item list-group-item-action active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="index.php?page=user-profile" class="list-group-item list-group-item-action"><i class="fa fa-user"></i> My Profile</a>
                <a href="index.php?page=all-sports" class="list-group-item list-group-item-action"><i class="fas fa-volleyball-ball"></i> All Sports</a>
                <a href="index.php?page=all-teams" class="list-group-item list-group-item-action"><i class="fas fa-users"></i> All Teams</a>
                <a href="index.php?page=all-players" class="list-group-item list-group-item-action"><i class="fa fa-users"></i> All Players</a>
                <a href="index.php?page=all-coaches" class="list-group-item list-group-item-action"><i class="fa fa-user-friends"></i> All Coaches</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="content">
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'] . '.php';
                } else {
                    $page = 'dashboard.php';
                }

                if (file_exists($page)) {
                    require_once $page;
                } else {
                    require_once '404.php';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery-3.5.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
