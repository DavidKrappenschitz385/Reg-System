<?php require_once 'db_con.php';
session_start();

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if(isset($_SESSION['user_login'])){
    $__username = $_SESSION['user_login'];
    $stmt = mysqli_prepare($db_con, "SELECT role FROM users WHERE username=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $__username);
    mysqli_stmt_execute($stmt);
    $__result = mysqli_stmt_get_result($stmt);
    $__roleRow = $__result ? mysqli_fetch_assoc($__result) : null;
    if ($__roleRow) {
        switch ($__roleRow['role']) {
            case 'admin':
                header('Location: index.php');
                break;
            case 'coach':
                header('Location: ../coach/index.php');
                break;
            case 'player':
                header('Location: ../player/index.php');
                break;
            default:
                header('Location: ../profile.php');
                break;
        }
        exit;
    }
}

if (isset($_POST['login'])) {
    // CSRF token validation
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $input_arr['input_csrf_error'] = "Invalid CSRF token!";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $input_arr = array();

        if (empty($username)) {
            $input_arr['input_user_error'] = "Username Is Required!";
        }

        if (empty($password)) {
            $input_arr['input_pass_error'] = "Password Is Required!";
        }

        if (count($input_arr) == 0) {
            $stmt = mysqli_prepare($db_con, "SELECT * FROM `users` WHERE `username` = ?");
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_login'] = $username;
                    $_SESSION['user_role'] = $row['role'];

                    switch ($row['role']) {
                        case 'admin':
                            header('Location: index.php');
                            break;
                        case 'coach':
                            header('Location: ../coach/index.php');
                            break;
                        case 'player':
                            header('Location: ../player/index.php');
                            break;
                        default:
                            header('Location: ../profile.php');
                            break;
                    }
                    exit;
                } else {
                    $wrongpass = "This password Wrong!";
                }
            } else {
                $usernameerr = "Username Not Found!";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container"><br>
          <h1 class="text-center">Login Users!</h1><hr><br>
          <div class="d-flex justify-content-center">
		<?php if(isset($usernameerr)){ ?> <div role="alert" aria-live="assertive" aria-atomic="true" align="center" class="toast alert alert-danger fade hide" data-delay="2000"><?php echo $usernameerr; ?></div><?php };?>
			<?php if(isset($worngpass)){ ?> <div role="alert" aria-live="assertive" aria-atomic="true" align="center" class="toast alert alert-danger fade hide" data-delay="2000"><?php echo $worngpass; ?></div><?php };?>
          </div>
          <div class="row animate__animated animate__pulse">
            <div class="col-md-4 offset-md-4">
		<form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
				  <div class="form-group row">
				    <div class="col-sm-12">
				      <input type="text" class="form-control" name="username" value="<?= isset($username)? $username: ''; ?>" placeholder="Username" id="inputEmail3"> <?php echo isset($input_arr['input_user_error'])? '<label>'.$input_arr['input_user_error'].'</label>':''; ?>
				    </div>
				  </div>
				  <div class="form-group row">
				    <div class="col-sm-12">
				      <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password"><label><?php echo isset($input_arr['input_pass_error'])? '<label>'.$input_arr['input_pass_error'].'</label>':''; ?>
				    </div>
				  </div>
				  <div class="text-center">
				      <button type="submit" name="login" class="btn btn-warning">Sign in</button>
				    </div>
				  </div>
				</form>
            </div>
          </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
        <script type="text/javascript">
	$('.toast').toast('show')

    </script>
  </body>
</html>