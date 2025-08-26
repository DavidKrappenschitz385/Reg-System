
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

$id = base64_decode($_GET['id']);
$photo = base64_decode($_GET['photo']);

$stmt = mysqli_prepare($db_con, "DELETE FROM `players` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if(mysqli_stmt_execute($stmt)){
	unlink('images/'.$photo);
	header('Location: index.php?page=all-players&delete=success');
}else{
	header('Location: index.php?page=all-players&delete=error');
}
?>