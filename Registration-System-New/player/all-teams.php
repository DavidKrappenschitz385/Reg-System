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
<h1 class="text-primary"><i class="fas fa-users"></i>  All Teams<small class="text-warning"> All Teams List!</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard </a></li>
     <li class="breadcrumb-item active" aria-current="page">All Teams</li>
  </ol>
</nav>
<table class="table  table-striped table-hover table-bordered" id="data">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SL</th>
      <th scope="col">Team Name</th>
      <th scope="col">Sport</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $query=mysqli_query($db_con,'SELECT t.*, s.name as sport_name FROM `teams` t JOIN `sports` s ON t.sport_id = s.id ORDER BY t.`id` DESC;');
      $i=1;
      while ($result = mysqli_fetch_array($query)) { ?>
      <tr>
        <?php
        echo '<td>'.$i.'</td>
          <td>'.ucwords($result['name']).'</td>
          <td>'.ucwords($result['sport_name']).'</td>
          <td>
            <a class="btn btn-xs btn-primary" href="index.php?page=request-to-join&id='.base64_encode($result['id']).'">
              <i class="fa fa-plus"></i> Request to Join</a>
          </td>';?>
      </tr>
     <?php $i++;} ?>

  </tbody>
</table>
