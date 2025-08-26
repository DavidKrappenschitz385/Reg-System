<?php
$user=  $_SESSION['user_login'];
  $corepage = explode('/', $_SERVER['PHP_SELF']);
    $corepage = end($corepage);
    if ($corepage!=='index.php') {
      if ($corepage==$corepage) {
        $corepage = explode('.', $corepage);
       header('Location: index.php?page='.$corepage[0]);
     }
    }

  $stmt = mysqli_prepare($db_con, "SELECT * FROM `users` WHERE `username` = ?");
  mysqli_stmt_bind_param($stmt, "s", $user);
  mysqli_stmt_execute($stmt);
  $query = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_array($query);

?>
<h1 class="text-primary"><i class="fas fa-user"></i> My Profile</h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard </a></li>
     <li class="breadcrumb-item active" aria-current="page">User Profile</li>
  </ol>
</nav>
<div class="row">
  <div class="col-sm-6">
    <table class="table table-bordered">
      <tr>
        <td>User ID</td>
        <td><?php echo $row['id']; ?></td>
      </tr>
      <tr>
        <td>Name</td>
        <td><?php echo ucwords($row['full_name']); ?></td>
      </tr>
      <tr>
        <td>Email</td>
        <td><?php echo $row['email']; ?></td>
      </tr>
      <tr>
        <td>Username</td>
        <td><?php echo ucwords($row['username']); ?></td>
      </tr>
      <tr>
        <td>Role</td>
        <td><?php echo ucwords($row['role']); ?></td>
      </tr>
      <tr>
        <td>Status</td>
        <td><?php echo ucwords($row['status']); ?></td>
      </tr>
      <tr>
        <td>Eligibility Document</td>
        <td>
          <?php if (!empty($row['eligibility_document'])) { ?>
            <a href="../admin/view_document.php?doc=<?php echo urlencode($row['eligibility_document']); ?>" target="_blank">View Document</a>
          <?php } else { ?>
            N/A
          <?php } ?>
        </td>
      </tr>
    </table>
    <a class="btn btn-warning pull-right" href="index.php?page=edit-user&id=<?php echo base64_encode($row['id']); ?>">Edit Profile</a>
  </div>
  <div class="col-sm-6">
    <h3>Profile Picture</h3>
    <?php if (!empty($row['photo'])) { ?>
    <a href="../admin/images/<?php echo $row['photo']; ?>">
      <img class="img-thumbnail" id="imguser" src="../admin/images/<?php echo $row['photo']; ?>" width="200px">
    </a>
    <?php } ?>
    <?php
        if (isset($_POST['upphoto'])) {
          if (!empty($row['photo']) && file_exists('../admin/images/'.$row['photo'])) {
            unlink('../admin/images/'.$row['photo']);
          }
          $photofile = $_FILES['userphoto']['tmp_name'];
          $ext = pathinfo($_FILES['userphoto']['name'], PATHINFO_EXTENSION);
          $upphoto = $user.date('YmdHis').'.'.$ext;
          $stmt = mysqli_prepare($db_con, "UPDATE `users` SET `photo` = ? WHERE `users`.`username` = ?");
          mysqli_stmt_bind_param($stmt, "ss", $upphoto, $user);
          if (mysqli_stmt_execute($stmt)) {
            move_uploaded_file($photofile, '../admin/images/'.$upphoto);
            // Refresh the page to show the new photo
            echo "<script>window.location.href = window.location.href;</script>";
          }else{
            echo "Profile Picture Not Uploaded";
          }
        }
     ?>
    <br>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="userphoto" required="" id="photo"><br>
      <input class="btn btn-info" type="submit" name="upphoto" value="Upload Photo">
    </form>
  </div>
</div>
