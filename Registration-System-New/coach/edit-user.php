<?php
  $corepage = explode('/', $_SERVER['PHP_SELF']);
    $corepage = end($corepage);
    if ($corepage!=='index.php') {
      if ($corepage==$corepage) {
        $corepage = explode('.', $corepage);
       header('Location: index.php?page='.$corepage[0]);
     }
    }

    $id = base64_decode($_GET['id']);
  if (isset($_POST['userupdate'])) {
	$full_name = $_POST['full_name'];
	$email = $_POST['email'];


    $stmt = mysqli_prepare($db_con, "UPDATE `users` SET `full_name`= ?, `email`= ? WHERE `id`= ?");
    mysqli_stmt_bind_param($stmt, "ssi", $full_name, $email, $id);
	if (mysqli_stmt_execute($stmt)) {
		$datainsert['insertsucess'] = '<p style="color: green;">User Updated!</p>';
		header('Location: index.php?page=user-profile&edit=success');
	}else{
		header('Location: index.php?page=user-profile&edit=error');
	}
  }
?>
<h1 class="text-primary"><i class="fas fa-user-plus"></i>  Edit My Info<small class="text-warning"> User</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard </a></li>
     <li class="breadcrumb-item" aria-current="page"><a href="index.php?page=user-profile">User Profile </a></li>
     <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
  </ol>
</nav>

	<?php
		if (isset($id)) {
            $stmt = mysqli_prepare($db_con, "SELECT `full_name`, `email` FROM `users` WHERE `id`= ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
			$row = mysqli_fetch_array($result);
		}
	 ?>
<div class="row">
<div class="col-sm-6">
	<form enctype="multipart/form-data" method="POST" action="">
		<div class="form-group">
		    <label for="full_name">Full Name</label>
		    <input name="full_name" type="text" class="form-control" id="full_name" value="<?php echo $row['full_name']; ?>" required="">
		</div>
		<div class="form-group">
		    <label for="email">Email</label>
		    <input name="email" type="email" class="form-control"  id="email" value="<?php echo $row['email']; ?>" required="">
		</div>

		<div class="form-group text-center">
		    <input name="userupdate" value="Update Profile" type="submit" class="btn btn-danger">
		</div>
	 </form>
</div>
</div>
