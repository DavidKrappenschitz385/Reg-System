<?php
  $corepage = explode('/', $_SERVER['PHP_SELF']);
    $corepage = end($corepage);
    if ($corepage!=='index.php') {
      if ($corepage==$corepage) {
        $corepage = explode('.', $corepage);
       header('Location: index.php?page='.$corepage[0]);
     }
    }

    if(isset($_GET['id'])){
        $team_id = base64_decode($_GET['id']);
        $player_id = $user['id'];

        $stmt = mysqli_prepare($db_con, "UPDATE players SET team_id = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $team_id, $player_id);
        if(mysqli_stmt_execute($stmt)){
            header('Location: index.php?page=all-teams&join_request=success');
        } else {
            header('Location: index.php?page=all-teams&join_request=error');
        }
    }
?>
