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
                $coach_id = $user['id'];
                $stmt = mysqli_prepare($db_con, "SELECT COUNT(*) as total_players FROM players WHERE team_id IN (SELECT id FROM teams WHERE coach_id = ?)");
                mysqli_stmt_bind_param($stmt, "i", $coach_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                echo $row['total_players'];
            ?>
            </span></div>
            <div class="clearfix"></div>
            <div class="float-sm-right">My Players</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<hr>
<h3>My Players</h3>
<table class="table  table-striped table-hover table-bordered" id="data">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SL</th>
      <th scope="col">Player ID</th>
      <th scope="col">Full Name</th>
      <th scope="col">Age</th>
      <th scope="col">Gender</th>
      <th scope="col">Sport</th>
      <th scope="col">Contact</th>
      <th scope="col">Photo</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $coach_id = $user['id'];
      $stmt = mysqli_prepare($db_con,'SELECT p.*, s.name as sport_name FROM `players` p JOIN `sports` s ON p.sport_id = s.id WHERE p.team_id IN (SELECT id FROM teams WHERE coach_id = ?)');
      mysqli_stmt_bind_param($stmt, "i", $coach_id);
      mysqli_stmt_execute($stmt);
      $query = mysqli_stmt_get_result($stmt);
      $i=1;
      while ($result = mysqli_fetch_array($query)) { ?>
      <tr>
        <?php
        echo '<td>'.$i.'</td>
          <td>'.$result['player_id'].'</td>
          <td>'.ucwords($result['full_name']).'</td>
          <td>'.$result['age'].'</td>
          <td>'.$result['gender'].'</td>
          <td>'.$result['sport_name'].'</td>
          <td>'.$result['contact_number'].'</td>
          <td><img src="../admin/images/'.$result['photo'].'" height="50px"></td>';?>
      </tr>
     <?php $i++;} ?>
  </tbody>
</table>
