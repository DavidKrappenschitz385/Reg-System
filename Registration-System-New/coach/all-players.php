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
<h1 class="text-primary"><i class="fas fa-users"></i>  All Players<small class="text-warning"> All Players List!</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard </a></li>
     <li class="breadcrumb-item active" aria-current="page">All Players</li>
  </ol>
</nav>
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
          <td><img src="../admin/images/'.$result['photo'].'" height="50px"></td>';?>
      </tr>
     <?php $i++;} ?>

  </tbody>
</table>
