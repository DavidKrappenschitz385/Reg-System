<?php
  $corepage = explode('/', $_SERVER['PHP_SELF']);
  $corepage = end($corepage);
  if ($corepage !== 'index.php') {
    if ($corepage == $corepage) {
      $corepage = explode('.', $corepage);
      header('Location: index.php?page=' . $corepage[0]);
    }
  }

  if (isset($_POST['addsport'])) {
    $name = $_POST['name'];
    $age_bracket = $_POST['age_bracket'];

    $input_error = array();
    if (empty($name)) { $input_error['name'] = "The Sport Name field is required."; }
    if (empty($age_bracket)) { $input_error['age_bracket'] = "The Age Bracket field is required."; }

    if (count($input_error) == 0) {
      $stmt = mysqli_prepare($db_con, "SELECT * FROM `sports` WHERE `name` = ?");
      mysqli_stmt_bind_param($stmt, "s", $name);
      mysqli_stmt_execute($stmt);
      $check_sport = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($check_sport) == 0) {
        $stmt = mysqli_prepare($db_con, "INSERT INTO `sports`(`name`, `age_bracket`) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $name, $age_bracket);

        if (mysqli_stmt_execute($stmt)) {
          $datainsert['insertsucess'] = '<p style="color: green;">Sport Added Successfully!</p>';
        } else {
          $datainsert['inserterror'] = '<p style="color: red;">Failed to Add Sport. Please check the information!</p>';
        }
      } else {
        $datainsert['inserterror'] = '<p style="color: red;">This Sport already exists!</p>';
      }
    }
  }
?>

<h1 class="text-primary"><i class="fas fa-plus"></i>  Add a Sport <small class="text-warning">New Sport Entry</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Sport</li>
  </ol>
</nav>

<div class="row">
  <div class="col-sm-6">
    <?php if (isset($datainsert)) { ?>
      <div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade" data-autohide="true" data-animation="true" data-delay="2000">
        <div class="toast-header">
          <strong class="mr-auto">Sport Registration</strong>
          <small><?php echo date('d-M-Y'); ?></small>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body">
          <?php
            if (isset($datainsert['insertsucess'])) {
              echo $datainsert['insertsucess'];
            }
            if (isset($datainsert['inserterror'])) {
              echo $datainsert['inserterror'];
            }
          ?>
        </div>
      </div>
    <?php } ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="name">Sport Name</label>
        <input name="name" type="text" class="form-control" id="name" value="<?= isset($name) ? $name : '' ; ?>" required>
        <?php if (isset($input_error['name'])) { echo '<div class="alert alert-danger mt-2" role="alert">' . $input_error['name'] . '</div>'; } ?>
      </div>

      <div class="form-group">
        <label for="age_bracket">Age Bracket</label>
        <input name="age_bracket" type="text" value="<?= isset($age_bracket) ? $age_bracket : '' ; ?>" class="form-control" id="age_bracket" required>
        <?php if (isset($input_error['age_bracket'])) { echo '<div class="alert alert-danger mt-2" role="alert">' . $input_error['age_bracket'] . '</div>'; } ?>
      </div>

      <div class="form-group text-center">
        <input name="addsport" value="Add Sport" type="submit" class="btn btn-danger">
      </div>
    </form>
  </div>
</div>
