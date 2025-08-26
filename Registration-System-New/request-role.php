<?php
require_once 'admin/db_con.php';
session_start();

if (!isset($_SESSION['user_login'])) {
    header('Location: admin/login.php');
    exit();
}

$username = $_SESSION['user_login'];
$stmt = mysqli_prepare($db_con, "SELECT * FROM `users` WHERE `username` = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user || $user['role'] !== 'default') {
    header('Location: profile.php');
    exit();
}

if (isset($_POST['request_role'])) {
    $role = $_POST['role'];
    $user_id = $user['id'];
    $full_name = $user['full_name'];

    $eligibility_document_name = '';
    if (!empty($_FILES['eligibility_document']['name'])) {
        $eligibility_document = explode('.', $_FILES['eligibility_document']['name']);
        $eligibility_document_ext = end($eligibility_document);
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];

        if (in_array(strtolower($eligibility_document_ext), $allowed_ext)) {
            $eligibility_document_name = 'doc_' . $user_id . '_' . date('YmdHis') . '.' . $eligibility_document_ext;
            $document_path = 'uploads/documents/' . $eligibility_document_name;

            if (!move_uploaded_file($_FILES['eligibility_document']['tmp_name'], $document_path)) {
                $error = "Failed to upload eligibility document.";
            }
        } else {
            $error = "Invalid file type for eligibility document. Only PDF, JPG, and PNG are allowed.";
        }
    } else {
        $error = "Eligibility document is required.";
    }

    $photo_name = '';
    if (!empty($_FILES['photo']['name'])) {
        $photo = explode('.', $_FILES['photo']['name']);
        $photo_ext = end($photo);
        $photo_name = 'user_' . $user_id . '_' . date('YmdHis') . '.' . $photo_ext;
        $photo_path = 'uploads/images/' . $photo_name;
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
            $error = "Failed to upload photo.";
        }
    }

    if (empty($error)) {
        $stmt = mysqli_prepare($db_con, "UPDATE `users` SET `role` = ?, `status` = 'pending', `eligibility_document` = ?, `photo` = ? WHERE `id` = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $role, $eligibility_document_name, $photo_name, $user_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            if ($role === 'player') {
                do {
                    $player_id = 'P' . rand(100000, 999999);
                    $stmt_check = mysqli_prepare($db_con, "SELECT `player_id` FROM `players` WHERE `player_id` = ?");
                    mysqli_stmt_bind_param($stmt_check, "s", $player_id);
                    mysqli_stmt_execute($stmt_check);
                    $check = mysqli_stmt_get_result($stmt_check);
                } while (mysqli_num_rows($check) > 0);

                $age = $_POST['age'] ?? null;
                $gender = $_POST['gender'] ?? '';
                $sport_id = $_POST['sport_id'] ?? '';
                $team_id = !empty($_POST['team_id']) ? $_POST['team_id'] : null;
                $contact_number = $_POST['contact_number'] ?? '';
                $address = $_POST['address'] ?? '';

                $stmt = mysqli_prepare($db_con, "INSERT INTO `players`(`user_id`, `player_id`, `full_name`, `age`, `gender`, `sport_id`, `team_id`, `contact_number`, `address`, `photo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "issisiiiss", $user_id, $player_id, $full_name, $age, $gender, $sport_id, $team_id, $contact_number, $address, $photo_name);
                mysqli_stmt_execute($stmt);

            } elseif ($role === 'coach') {
                $experience_years = $_POST['experience_years'] ?? 0;
                $certifications = $_POST['certifications'] ?? '';
                $sport_id = $_POST['coach_sport_id'] ?? '';
                $team_id = !empty($_POST['coach_team_id']) ? $_POST['coach_team_id'] : null;
                $stmt = mysqli_prepare($db_con, "INSERT INTO `coaches`(`user_id`, `full_name`, `experience_years`, `certifications`, `sport_id`, `team_id`) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "isissi", $user_id, $full_name, $experience_years, $certifications, $sport_id, $team_id);
                mysqli_stmt_execute($stmt);
            }
            $_SESSION['message'] = "Your role request has been submitted. Please wait for admin approval.";
            header('Location: profile.php');
            exit();
        } else {
            $error = "Failed to submit role request. Please try again.";
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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Request Role</title>
</head>
<body>
<div class="container">
    <br>
    <h1 class="text-center">Request a Role</h1>
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            <form method="POST" action="request-role.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">Select a role</option>
                        <option value="player">Player</option>
                        <option value="coach">Coach</option>
                    </select>
                </div>

                <div id="player_fields" style="display:none;">
                    <hr>
                    <h5>Player Details</h5>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input name="age" type="number" class="form-control" id="age" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" class="form-control" id="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input name="address" type="text" class="form-control" id="address" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact #</label>
                        <input name="contact_number" type="text" class="form-control" id="contact_number" pattern="09[0-9]{9}" placeholder="09XXXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="sport_id">Sport</label>
                        <select name="sport_id" class="form-control" id="sport_id" required>
                            <option value="">Select Sport</option>
                            <?php
                            $sports_query = mysqli_query($db_con, "SELECT * FROM `sports` ORDER BY `name` ASC");
                            if ($sports_query) {
                                while ($sport = mysqli_fetch_assoc($sports_query)) {
                                    echo '<option value="'.$sport['id'].'">'.ucwords($sport['name']).' ('.$sport['age_bracket'].')</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="team_id">Team (Optional)</label>
                        <select name="team_id" class="form-control" id="team_id">
                            <option value="">Select Team</option>
                            <?php
                            $teams_query = mysqli_query($db_con, "SELECT * FROM `teams` ORDER BY `name` ASC");
                            if ($teams_query) {
                                while ($team = mysqli_fetch_assoc($teams_query)) {
                                    echo '<option value="'.$team['id'].'">'.ucwords($team['name']).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">ID Picture</label>
                        <input name="photo" type="file" class="form-control" id="photo" required>
                    </div>
                    <div class="form-group">
                        <label for="eligibility_document_player">Eligibility Document (PDF, JPG, PNG)</label>
                        <input name="eligibility_document" type="file" class="form-control" id="eligibility_document_player" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                </div>

                <div id="coach_fields" style="display:none;">
                    <hr>
                    <h5>Coach Details</h5>
                    <div class="form-group">
                        <label for="experience_years">Years of Experience</label>
                        <input name="experience_years" type="number" min="0" class="form-control" id="experience_years">
                    </div>
                    <div class="form-group">
                        <label for="certifications">Certifications</label>
                        <textarea name="certifications" class="form-control" id="certifications" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="coach_sport_id">Sport</label>
                        <select name="coach_sport_id" class="form-control" id="coach_sport_id" required>
                            <option value="">Select Sport</option>
                            <?php
                            $sports_query2 = mysqli_query($db_con, "SELECT * FROM `sports` ORDER BY `name` ASC");
                            if ($sports_query2) {
                                while ($sport2 = mysqli_fetch_assoc($sports_query2)) {
                                    echo '<option value="'.$sport2['id'].'">'.ucwords($sport2['name']).' ('.$sport2['age_bracket'].')</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
.
                        <label for="coach_team_id">Team (Optional)</label>
                        <select name="coach_team_id" class="form-control" id="coach_team_id">
                            <option value="">Select Team</option>
                            <?php
                            $teams_query2 = mysqli_query($db_con, "SELECT * FROM `teams` ORDER BY `name` ASC");
                            if ($teams_query2) {
                                while ($team2 = mysqli_fetch_assoc($teams_query2)) {
                                    echo '<option value="'.$team2['id'].'">'.ucwords($team2['name']).'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo_coach">ID Picture</label>
                        <input name="photo" type="file" class="form-control" id="photo_coach">
                    </div>
                    <div class="form-group">
                        <label for="eligibility_document_coach">Eligibility Document (PDF, JPG, PNG)</label>
                        <input name="eligibility_document" type="file" class="form-control" id="eligibility_document_coach" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                </div>

                <button type="submit" name="request_role" class="btn btn-primary">Submit Request</button>
                <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
            </form>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="js/jquery-3.5.1.min..js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    (function(){
        var select = document.getElementById('role');
        var playerFieldsContainer = document.getElementById('player_fields');
        var coachFieldsContainer = document.getElementById('coach_fields');

        var playerFields = playerFieldsContainer.querySelectorAll('input, select');
        var coachFields = coachFieldsContainer.querySelectorAll('input, select, textarea');

        function setRequired(elements, isRequired) {
            elements.forEach(function(el) {
                el.disabled = !isRequired;
                // team_id is optional for players, and several fields are optional for coaches.
                if (el.name === 'team_id' || el.name === 'coach_team_id' || el.name === 'experience_years' || el.name === 'certifications' || el.id === 'photo_coach') {
                    el.required = false;
                } else {
                    el.required = isRequired;
                }
            });
        }

        function toggle(){
            var v = select.value;
            if (v === 'player') {
                playerFieldsContainer.style.display = 'block';
                coachFieldsContainer.style.display = 'none';
                setRequired(playerFields, true);
                setRequired(coachFields, false);
            } else if (v === 'coach') {
                playerFieldsContainer.style.display = 'none';
                coachFieldsContainer.style.display = 'block';
                setRequired(playerFields, false);
                setRequired(coachFields, true);
            } else {
                playerFieldsContainer.style.display = 'none';
                coachFieldsContainer.style.display = 'none';
                setRequired(playerFields, false);
                setRequired(coachFields, false);
            }
        }

        select && select.addEventListener('change', toggle);
        toggle(); // run on page load to set initial state
    })();
</script>
</body>
</html>
