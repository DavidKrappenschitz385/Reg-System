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
            <div class="float-sm-right">&nbsp;<span style="font-size: 30px"><?php $players=mysqli_query($db_con,'SELECT * FROM `players`'); $players= mysqli_num_rows($players); echo $players; ?></span></div>
            <div class="clearfix"></div>
            <div class="float-sm-right">Total Players</div>
          </div>
        </div>
      </div>
      <div class="list-group-item-primary list-group-item list-group-item-action">
        <a href="index.php?page=all-players">
        <div class="row">
          <div class="col-sm-8">
            <p class="">All Players</p>
          </div>
          <div class="col-sm-4">
            <i class="fa fa-arrow-right float-sm-right"></i>
          </div>
        </div>
        </a>
      </div>
    </div>
  </div>

  <div class="col-sm-4">
     <div class="card text-white bg-success mb-3">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-4">
            <i class="fa fa-chalkboard-teacher fa-3x"></i>
          </div>
          <div class="col-sm-8">
            <div class="float-sm-right">&nbsp;<span style="font-size: 30px"><?php $coaches=mysqli_query($db_con,'SELECT * FROM `coaches`'); $coaches= mysqli_num_rows($coaches); echo $coaches; ?></span></div>
            <div class="clearfix"></div>
            <div class="float-sm-right">Total Coaches</div>
          </div>
        </div>
      </div>
      <div class="list-group-item-primary list-group-item list-group-item-action">
        <a href="index.php?page=all-coaches">
        <div class="row">
          <div class="col-sm-8">
            <p class="">All Coaches</p>
          </div>
          <div class="col-sm-4">
            <i class="fa fa-arrow-right float-sm-right"></i>
          </div>
        </div>
        </a>
      </div>
    </div>
  </div>

  <div class="col-sm-4">
     <div class="card text-white bg-info mb-3">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-4">
            <i class="fa fa-users fa-3x"></i>
          </div>
          <div class="col-sm-8">
            <div class="float-sm-right">&nbsp;<span style="font-size: 30px"><?php $tusers=mysqli_query($db_con,'SELECT * FROM `users`'); $tusers= mysqli_num_rows($tusers); echo $tusers; ?></span></div>
            <div class="clearfix"></div>
            <div class="float-sm-right">Total Users</div>
          </div>
        </div>
      </div>
      <div class="list-group-item-primary list-group-item list-group-item-action">
         <a href="index.php?page=all-users">
        <div class="row">
          <div class="col-sm-8">
            <p class="">All Users</p>
          </div>
          <div class="col-sm-4">
           <i class="fa fa-arrow-right float-sm-right"></i>
          </div>
        </div>
        </a>
      </div>
    </div>
  </div>
</div>
<hr>
<h3>Players</h3>
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
      $query=mysqli_query($db_con,'SELECT p.*, s.name as sport_name FROM `players` p JOIN `sports` s ON p.sport_id = s.id LEFT JOIN `users` u ON p.user_id = u.id WHERE p.user_id IS NULL OR (u.status = \'approved\' AND u.role = \'player\') ORDER BY p.`registration_date` DESC;');
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
          <td><img src="images/'.$result['photo'].'" height="50px"></td>';?>
      </tr>
     <?php $i++;} ?>

  </tbody>
</table>
<hr>
<h3>Coaches</h3>
<table class="table  table-striped table-hover table-bordered" id="data">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SL</th>
      <th scope="col">Full Name</th>
      <th scope="col">Sport</th>
      <th scope="col">Team</th>
      <th scope="col">Experience (Years)</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $q2=mysqli_query($db_con,'SELECT c.*, s.name as sport_name, t.name as team_name FROM `coaches` c LEFT JOIN `sports` s ON c.sport_id = s.id LEFT JOIN `teams` t ON c.team_id = t.id LEFT JOIN `users` u ON c.user_id = u.id WHERE c.user_id IS NULL OR (u.status = \'approved\' AND u.role = \'coach\') ORDER BY c.`id` DESC LIMIT 10;');
      $j=1;
      while ($c = mysqli_fetch_array($q2)) { ?>
      <tr>
        <?php
        echo '<td>'.$j.'</td>
          <td>'.ucwords($c['full_name']).'</td>
          <td>'.ucwords($c['sport_name']).'</td>
          <td>'.ucwords($c['team_name']).'</td>
          <td>'.$c['experience_years'].'</td>';?>
      </tr>
     <?php $j++;} ?>

  </tbody>
</table>