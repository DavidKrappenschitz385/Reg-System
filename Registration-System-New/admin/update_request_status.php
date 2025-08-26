<?php
require_once 'db_con.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_login']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.0 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'You do not have permission to perform this action.']);
    exit;
}

if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $stmt = mysqli_prepare($db_con, "UPDATE `users` SET `status` = 'approved' WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'User approved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to approve user.']);
    }
} elseif (isset($_GET['decline'])) {
    $id = $_GET['decline'];
    $stmt = mysqli_prepare($db_con, "UPDATE `users` SET `status` = 'declined' WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'User declined successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to decline user.']);
    }
} else {
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'No action specified.']);
}
?>
