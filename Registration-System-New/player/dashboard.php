<?php
  $corepage = explode('/', $_SERVER['PHP_SELF']);
    $corepage = end($corepage);
    if ($corepage!=='index.php') {
      if ($corepage==$corepage) {
        $corepage = explode('.', $corepage);
       header('Location: index.php?page='.$corepage[0]);
     }
    }
?>

<h1><a href="index.php"><i class="fas fa-tachometer-alt"></i>  Dashboard</a> <small>Statistics Overview</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-user"></i> Dashboard</li>
  </ol>
</nav>

<div class="row student">
  <div class="col-sm-4">
     <div class="card text-white bg-primary mb-3">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-4">
            <i class="fa fa-users fa-3x"></i>
          </div>
          <div class="col-sm-8">
            <div class="float-sm-right">&nbsp;<span style="font-size: 30px">
            <?php
                $player_id = $user['id'];
                $stmt = mysqli_prepare($db_con, "SELECT t.name as team_name FROM teams t JOIN players p ON t.id = p.team_id WHERE p.user_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $player_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                echo $row ? $row['team_name'] : 'No Team';
            ?>
            </span></div>
            <div class="clearfix"></div>
            <div class="float-sm-right">My Team</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
