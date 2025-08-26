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
<h1 class="text-primary"><i class="fa fa-user-friends"></i>  All Coaches<small class="text-warning"> All Coaches List!</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard </a></li>
     <li class="breadcrumb-item active" aria-current="page">All Coaches</li>
  </ol>
</nav>
<table class="table  table-striped table-hover table-bordered" id="data">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SL</th>
      <th scope="col">Full Name</th>
      <th scope="col">Sport</th>
      <th scope="col">Team</th>
      <th scope="col">Experience (Years)</th>
      <th scope="col">Certifications</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $query=mysqli_query($db_con,'SELECT c.*, s.name as sport_name, t.name as team_name FROM `coaches` c LEFT JOIN `sports` s ON c.sport_id = s.id LEFT JOIN `teams` t ON c.team_id = t.id LEFT JOIN `users` u ON c.user_id = u.id WHERE c.user_id IS NULL OR (u.status = \'approved\' AND u.role = \'coach\') ORDER BY c.`id` DESC;');
      $i=1;
      while ($result = mysqli_fetch_array($query)) { ?>
      <tr>
        <?php
        echo '<td>'.$i.'</td>
          <td>'.ucwords($result['full_name']).'</td>
          <td>'.ucwords($result['sport_name']).'</td>
          <td>'.ucwords($result['team_name']).'</td>
          <td>'.$result['experience_years'].'</td>
          <td>'.htmlspecialchars($result['certifications']).'</td>';?>
      </tr>
     <?php $i++;} ?>

  </tbody>
</table>
