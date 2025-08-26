<?php
  $corepage = explode('/', $_SERVER['PHP_SELF']);
  $corepage = end($corepage);
  if ($corepage !== 'index.php') {
    if ($corepage == $corepage) {
      $corepage = explode('.', $corepage);
      header('Location: index.php?page=' . $corepage[0]);
    }
  }

  $id = base64_decode($_GET['id']);

  if (isset($_POST['updatesport'])) {
    $name = $_POST['name'];
    $age_bracket = $_POST['age_bracket'];

    $stmt = mysqli_prepare($db_con, "UPDATE `sports` SET `name` = ?, `age_bracket` = ? WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $name, $age_bracket, $id);

    if (mysqli_stmt_execute($stmt)) {
      header('Location: index.php?page=all-sports&edit=success');
    } else {
      header('Location: index.php?page=all-sports&edit=error');
    }
  }
?>

<h1 class="text-primary"><i class="fas fa-edit"></i> Edit Sport Information <small class="text-warning">Update Sport</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="index.php?page=all-sports">All Sports</a></li>
    <li class="breadcrumb-item active">Edit Sport</li>
  </ol>
</nav>

<?php
  if (isset($id)) {
    $stmt = mysqli_prepare($db_con, "SELECT `id`, `name`, `age_bracket` FROM `sports` WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
  }
?>

<div class="row">
  <div class="col-sm-6">
    <form method="POST" action="">
      <div class="form-group">
        <label for="name">Sport Name</label>
        <input name="name" type="text" class="form-control" id="name" value="<?= $row['name']; ?>" required>
      </div>

      <div class="form-group">
        <label for="age_bracket">Age Bracket</label>
        <input name="age_bracket" type="text" class="form-control" id="age_bracket" value="<?= $row['age_bracket']; ?>" required>
      </div>

      <div class="form-group text-center">
        <input name="updatesport" value="Update Sport" type="submit" class="btn btn-danger">
      </div>
    </form>
  </div>
</div>
