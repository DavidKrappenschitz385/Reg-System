<?php
require_once 'db_con.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_login'])) {
    header('HTTP/1.0 403 Forbidden');
    echo 'You are not logged in.';
    exit;
}

if (!isset($_GET['doc']) || empty($_GET['doc'])) {
    header('HTTP/1.0 400 Bad Request');
    echo 'No document specified.';
    exit;
}

$doc_name = basename($_GET['doc']);
$doc_path = 'documents/' . $doc_name;

if (!file_exists($doc_path)) {
    header('HTTP/1.0 404 Not Found');
    echo 'Document not found.';
    exit;
}

$current_user_username = $_SESSION['user_login'];
$stmt = mysqli_prepare($db_con, "SELECT `id`, `role` FROM `users` WHERE `username` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $current_user_username);
mysqli_stmt_execute($stmt);
$user_res = mysqli_stmt_get_result($stmt);
$current_user = $user_res ? mysqli_fetch_assoc($user_res) : null;

if (!$current_user) {
    header('HTTP/1.0 403 Forbidden');
    echo 'Could not identify current user.';
    exit;
}

$stmt = mysqli_prepare($db_con, "SELECT `id` FROM `users` WHERE `eligibility_document` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $doc_name);
mysqli_stmt_execute($stmt);
$doc_res = mysqli_stmt_get_result($stmt);
$doc_owner = $doc_res ? mysqli_fetch_assoc($doc_res) : null;

if (!$doc_owner) {
    header('HTTP/1.0 404 Not Found');
    echo 'Document owner not found.';
    exit;
}

$is_admin = $current_user['role'] === 'admin';
$is_owner = $current_user['id'] === $doc_owner['id'];

if (!$is_admin && !$is_owner) {
    header('HTTP/1.0 403 Forbidden');
    echo 'You do not have permission to view this document.';
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $doc_path);
finfo_close($finfo);

header('Content-Type: ' . $mime_type);
header('Content-Disposition: inline; filename="' . $doc_name . '"');
header('Content-Length: ' . filesize($doc_path));
readfile($doc_path);
exit;
?>
