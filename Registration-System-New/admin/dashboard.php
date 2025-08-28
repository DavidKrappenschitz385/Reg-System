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
<?php
$player_requests = [];
$coach_requests = [];

// Fetch player requests with details
$player_query = "
    SELECT u.id, u.full_name, u.email, u.username, u.eligibility_document, u.photo, p.age, p.gender, p.address, p.contact_number, s.name as sport_name, t.name as team_name
    FROM `users` u
    LEFT JOIN `players` p ON u.id = p.user_id
    LEFT JOIN `sports` s ON p.sport_id = s.id
    LEFT JOIN `teams` t ON p.team_id = t.id
    WHERE u.status = 'pending' AND u.role = 'player'
";
$player_result = mysqli_query($db_con, $player_query);
if ($player_result) {
    while ($row = mysqli_fetch_assoc($player_result)) {
        $player_requests[] = $row;
    }
}

// Fetch coach requests
$coach_query = "
    SELECT u.id, u.full_name, u.email, u.username, u.eligibility_document, u.photo, c.experience_years, c.certifications, s.name as sport_name, t.name as team_name
    FROM `users` u
    LEFT JOIN `coaches` c ON u.id = c.user_id
    LEFT JOIN `sports` s ON c.sport_id = s.id
    LEFT JOIN `teams` t ON c.team_id = t.id
    WHERE u.status = 'pending' AND u.role = 'coach'
";
$coach_result = mysqli_query($db_con, $coach_query);
if ($coach_result) {
    while ($row = mysqli_fetch_assoc($coach_result)) {
        $coach_requests[] = $row;
    }
}
?>

<h3 class="text-secondary">Player Requests</h3>
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="player-data">
        <thead class="thead-dark">
        <tr>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Contact #</th>
            <th>Sport</th>
            <th>Team</th>
            <th>Document</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($player_requests as $row) { ?>
            <tr>
                <td><img src="../uploads/images/<?php echo $row['photo']; ?>" alt="Player Photo" style="width: 50px; height: 50px;"></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['age']); ?></td>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                <td><?php echo htmlspecialchars($row['sport_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['team_name'] ?? 'N/A'); ?></td>
                <td>
                    <?php if (!empty($row['eligibility_document'])) { ?>
                        <a href="index.php?page=view_document&doc=<?php echo urlencode($row['eligibility_document']); ?>" class="btn btn-info btn-sm">View Document</a>
                    <?php } else { ?>
                        N/A
                    <?php } ?>
                </td>
                <td>
                    <a href="index.php?approve=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                    <a href="index.php?decline=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Decline</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<h3 class="text-secondary mt-5">Coach Requests</h3>
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="coach-data">
        <thead class="thead-dark">
        <tr>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Experience</th>
            <th>Certifications</th>
            <th>Sport</th>
            <th>Team</th>
            <th>Document</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($coach_requests as $row) { ?>
            <tr>
                <td><img src="../uploads/images/<?php echo $row['photo']; ?>" alt="Coach Photo" style="width: 50px; height: 50px;"></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['experience_years'] ?? 'N/A') . ' years'; ?></td>
                <td><?php echo htmlspecialchars($row['certifications'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['sport_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['team_name'] ?? 'N/A'); ?></td>
                <td>
                    <?php if (!empty($row['eligibility_document'])) { ?>
                        <a href="index.php?page=view_document&doc=<?php echo urlencode($row['eligibility_document']); ?>" class="btn btn-info btn-sm">View Document</a>
                    <?php } else { ?>
                        N/A
                    <?php } ?>
                </td>
                <td>
                    <a href="index.php?approve=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                    <a href="index.php?decline=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Decline</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
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