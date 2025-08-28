<?php
require_once 'db_con.php';
session_start();

if (!isset($_SESSION['user_login'])) {
  header('Location: login.php');
  exit;
}

$__current_username = $_SESSION['user_login'];
$stmt = mysqli_prepare($db_con, "SELECT `role` FROM `users` WHERE `username` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $__current_username);
mysqli_stmt_execute($stmt);
$__user_result = mysqli_stmt_get_result($stmt);
$__user_row = $__user_result ? mysqli_fetch_assoc($__user_result) : null;
if (!$__user_row || $__user_row['role'] !== 'admin') {
  header('Location: ../profile.php');
  exit;
}

if (isset($_GET['approve'])) {
    $user_id = intval($_GET['approve']);
    $stmt = mysqli_prepare($db_con, "UPDATE `users` SET `status` = 'approved' WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    header('Location: index.php?page=dashboard');
    exit;
}

if (isset($_GET['decline'])) {
    $user_id = intval($_GET['decline']);
    $stmt = mysqli_prepare($db_con, "UPDATE `users` SET `status` = 'declined' WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    header('Location: index.php?page=dashboard');
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../css/fontawesome.min.css">
    <link rel="stylesheet" href="../css/solid.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/fontawesome.min.js"></script>
    <script src="../js/script.js"></script>
    <title>Admin Dashboard</title>
  </head>
  <body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php"><i class="fas fa-chart-line fa-3x"></i></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse collapse justify-content-end" id="navbarSupportedContent">
    <?php
    $showuser = $_SESSION['user_login'];
    $stmt = mysqli_prepare($db_con, "SELECT * FROM `users` WHERE `username`= ?");
    mysqli_stmt_bind_param($stmt, "s", $showuser);
    mysqli_stmt_execute($stmt);
    $haha = mysqli_stmt_get_result($stmt);
    $showrow = mysqli_fetch_array($haha);
    ?>
    <ul class="nav navbar-nav ">
      <li class="nav-item"><a class="nav-link" href="index.php?page=user-profile"><i class="fa fa-user"></i>
          Welcome <?php echo ucwords($showrow['username']); ?>!</a></li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=add-player"><i class="fa fa-user-plus"></i> Add Player</a>
      </li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=user-profile"><i class="fa fa-user"></i>Admin Profile</a>
      </li>
      <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
    </ul>
  </div>
</nav>
<br>
<div class="container">
  <div class="row">
    <div class="col-md-3">
      <div class="list-group">
        <a href="index.php?page=dashboard" class="list-group-item list-group-item-action active">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="index.php?page=role-requests" class="list-group-item list-group-item-action"><i
            class="fas fa-user-check"></i> Role Requests</a>
        <a href="index.php?page=add-sport" class="list-group-item list-group-item-action"><i class="fas fa-plus"></i> Add Sport</a>
        <a href="index.php?page=all-sports" class="list-group-item list-group-item-action"><i
            class="fas fa-volleyball-ball"></i> All Sports</a>
        <a href="index.php?page=add-team" class="list-group-item list-group-item-action"><i class="fas fa-users-cog"></i> Add Team</a>
        <a href="index.php?page=all-teams" class="list-group-item list-group-item-action"><i class="fas fa-users"></i> All Teams</a>
        <a href="index.php?page=add-player" class="list-group-item list-group-item-action"><i class="fa fa-user-plus"></i> Add Player</a>
        <a href="index.php?page=all-players" class="list-group-item list-group-item-action"><i class="fa fa-users"></i> All Players</a>
        <a href="index.php?page=add-coach" class="list-group-item list-group-item-action"><i class="fa fa-chalkboard-teacher"></i> Add Coach</a>
        <a href="index.php?page=all-coaches" class="list-group-item list-group-item-action"><i class="fa fa-user-friends"></i> All Coaches</a>
        <a href="index.php?page=all-users" class="list-group-item list-group-item-action"><i class="fa fa-users"></i> All Users</a>
        <a href="index.php?page=user-profile" class="list-group-item list-group-item-action"><i class="fa fa-user"></i> Admin User Profile</a>
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
<div class="clearfix"></div>
<footer>
  <div class="container">
    <p>Copyright &copy; 2016 to <?php echo date('Y') ?></p>
  </div>
</footer>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript">
  jQuery('.toast').toast('show');
</script>
</body>
</html>
