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

  if (isset($_POST['updateteam'])) {
    $name = $_POST['name'];
    $sport_id = $_POST['sport_id'];

    $stmt = mysqli_prepare($db_con, "UPDATE `teams` SET `name` = ?, `sport_id` = ? WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "sii", $name, $sport_id, $id);

    if (mysqli_stmt_execute($stmt)) {
      header('Location: index.php?page=all-teams&edit=success');
    } else {
      header('Location: index.php?page=all-teams&edit=error');
    }
  }
?>

<h1 class="text-primary"><i class="fas fa-edit"></i> Edit Team Information <small class="text-warning">Update Team</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="index.php?page=all-teams">All Teams</a></li>
    <li class="breadcrumb-item active">Edit Team</li>
  </ol>
</nav>

<?php
  if (isset($id)) {
    $stmt = mysqli_prepare($db_con, "SELECT * FROM `teams` WHERE `id` = ?");
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
        <label for="name">Team Name</label>
        <input name="name" type="text" class="form-control" id="name" value="<?= $row['name']; ?>" required>
      </div>

      <div class="form-group">
        <label for="sport_id">Sport</label>
        <select name="sport_id" class="form-control" id="sport_id" required>
          <?php
            $sports_query = mysqli_query($db_con, "SELECT * FROM `sports` ORDER BY `name` ASC");
            if ($sports_query) {
                while ($sport = mysqli_fetch_assoc($sports_query)) {
                  $selected = ($sport['id'] == $row['sport_id']) ? 'selected' : '';
                  echo '<option value="'.$sport['id'].'" '.$selected.'>'.ucwords($sport['name']).' ('.$sport['age_bracket'].')</option>';
                }
            }
          ?>
        </select>
      </div>

      <div class="form-group text-center">
        <input name="updateteam" value="Update Team" type="submit" class="btn btn-danger">
      </div>
    </form>
  </div>
</div>
