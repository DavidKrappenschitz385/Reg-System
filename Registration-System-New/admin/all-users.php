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
<h1 class="text-primary"><i class="fas fa-users"></i>  All Users<small class="text-warning"> All Users List</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard </a></li>
     <li class="breadcrumb-item active" aria-current="page">All Users</li>
  </ol>
</nav>

<table class="table  table-striped table-hover table-bordered" id="data">
  <thead class="thead-dark">
    <tr>
      <th scope="col">SL</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Username</th>
      <th scope="col">Role</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $query=mysqli_query($db_con,'SELECT * FROM `users`');
      $i=1;
      while ($result = mysqli_fetch_array($query)) { ?>
      <tr>
        <?php
        echo '<td>'.$i.'</td>
          <td>'.ucwords($result['full_name']).'</td>
          <td>'.$result['email'].'</td>
          <td>'.ucwords($result['username']).'</td>
          <td>'.ucwords($result['role']).'</td>
          <td>'.$result['status'].'</td>
          <td>
            <a class="btn btn-xs btn-warning" href="index.php?page=edit-user&id='.base64_encode($result['id']).'">
              <i class="fa fa-edit"></i> Edit</a>
            &nbsp;
            <a class="btn btn-xs btn-danger" onclick="javascript:confirmationDelete($(this));return false;" href="index.php?page=delete-user&id='.base64_encode($result['id']).'">
             <i class="fas fa-trash-alt"></i> Delete</a>';

        if ($result['role'] == 'coach') {
            echo '&nbsp; <a class="btn btn-xs btn-info" href="index.php?page=view-members&id='.base64_encode($result['id']).'">
            <i class="fa fa-eye"></i> View Members</a>';
        }

        echo '</td>';?>
      </tr>
     <?php $i++;} ?>

  </tbody>
</table>
<script type="text/javascript">
  function confirmationDelete(anchor)
{
   var conf = confirm('Are you sure want to delete this record?');
   if(conf)
      window.location=anchor.attr("href");
}
</script>