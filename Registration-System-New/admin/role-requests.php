<?php
require_once 'db_con.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_login'])) {
    header('Location: login.php');
    exit;
}
$__u = $_SESSION['user_login'];
$stmt = mysqli_prepare($db_con, "SELECT `role` FROM `users` WHERE `username` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $__u);
mysqli_stmt_execute($stmt);
$__res = mysqli_stmt_get_result($stmt);
$__row = $__res ? mysqli_fetch_assoc($__res) : null;
if (!$__row || $__row['role'] !== 'admin') {
    header('Location: ../profile.php');
    exit;
}
?>

<h1 class="text-primary"><i class="fas fa-user-check"></i> Role Requests</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Role Requests</li>
    </ol>
</nav>

<?php
$player_requests = [];
$coach_requests = [];

// Fetch player requests with details
$player_query = "
    SELECT u.id, u.full_name, u.email, u.username, u.eligibility_document, u.photo, p.age, p.gender, p.address, p.contact_number, s.name as sport_name, t.name as team_name
    FROM `users` u
    LEFT JOIN `players` p ON u.id = p.user_id
    LEFT JOIN `sports` s ON p.sport_id = s.id
    LEFT JOIN `teams` t ON p.team_id = t.id
    WHERE u.status = 'pending' AND u.role = 'player'
";
$player_result = mysqli_query($db_con, $player_query);
if ($player_result) {
    while ($row = mysqli_fetch_assoc($player_result)) {
        $player_requests[] = $row;
    }
}

// Fetch coach requests
$coach_query = "
    SELECT u.id, u.full_name, u.email, u.username, u.eligibility_document, u.photo, c.experience_years, c.certifications, s.name as sport_name, t.name as team_name
    FROM `users` u
    LEFT JOIN `coaches` c ON u.id = c.user_id
    LEFT JOIN `sports` s ON c.sport_id = s.id
    LEFT JOIN `teams` t ON c.team_id = t.id
    WHERE u.status = 'pending' AND u.role = 'coach'
";
$coach_result = mysqli_query($db_con, $coach_query);
if ($coach_result) {
    while ($row = mysqli_fetch_assoc($coach_result)) {
        $coach_requests[] = $row;
    }
}
?>

<h2 class="text-secondary">Player Requests</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="player-data">
        <thead class="thead-dark">
        <tr>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Contact #</th>
            <th>Sport</th>
            <th>Team</th>
            <th>Document</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($player_requests as $row) { ?>
            <tr>
                <td><img src="../uploads/images/<?php echo $row['photo']; ?>" alt="Player Photo" style="width: 50px; height: 50px;"></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['age']); ?></td>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                <td><?php echo htmlspecialchars($row['sport_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['team_name'] ?? 'N/A'); ?></td>
                <td>
                    <?php if (!empty($row['eligibility_document'])) { ?>
                        <a href="#" data-doc="../uploads/documents/<?php echo urlencode($row['eligibility_document']); ?>" data-user-id="<?php echo $row['id']; ?>" class="btn btn-info btn-sm view-doc-btn">View Document</a>
                    <?php } else { ?>
                        N/A
                    <?php } ?>
                </td>
                <td>
                    <a href="#" class="btn btn-success btn-sm approve-btn" data-user-id="<?php echo $row['id']; ?>">Approve</a>
                    <a href="#" class="btn btn-danger btn-sm decline-btn" data-user-id="<?php echo $row['id']; ?>">Decline</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('.view-doc-btn').on('click', function(e) {
            e.preventDefault();
            var docUrl = $(this).data('doc');
            $('#viewDocumentModal iframe').attr('src', docUrl);
            $('#viewDocumentModal').modal('show');
        });

        $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            var userId = $(this).data('user-id');
            $.ajax({
                url: 'update_request_status.php?approve=' + userId,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while processing the request.');
                }
            });
        });

        $(document).on('click', '.decline-btn', function(e) {
            e.preventDefault();
            var userId = $(this).data('user-id');
            $.ajax({
                url: 'update_request_status.php?decline=' + userId,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while processing the request.');
                }
            });
        });
    });
</script>

<!-- Document Viewer Modal -->
<div class="modal fade" id="viewDocumentModal" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDocumentModalLabel">View Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="" frameborder="0" width="100%" height="500px"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<h2 class="text-secondary mt-5">Coach Requests</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="coach-data">
        <thead class="thead-dark">
        <tr>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Experience</th>
            <th>Certifications</th>
            <th>Sport</th>
            <th>Team</th>
            <th>Document</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($coach_requests as $row) { ?>
            <tr>
                <td><img src="../uploads/images/<?php echo $row['photo']; ?>" alt="Coach Photo" style="width: 50px; height: 50px;"></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['experience_years'] ?? 'N/A') . ' years'; ?></td>
                <td><?php echo htmlspecialchars($row['certifications'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['sport_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['team_name'] ?? 'N/A'); ?></td>
                <td>
                    <?php if (!empty($row['eligibility_document'])) { ?>
                        <a href="#" data-doc="../uploads/documents/<?php echo urlencode($row['eligibility_document']); ?>" data-user-id="<?php echo $row['id']; ?>" class="btn btn-info btn-sm view-doc-btn">View Document</a>
                    <?php } else { ?>
                        N/A
                    <?php } ?>
                </td>
                <td>
                    <a href="#" class="btn btn-success btn-sm approve-btn" data-user-id="<?php echo $row['id']; ?>">Approve</a>
                    <a href="#" class="btn btn-danger btn-sm decline-btn" data-user-id="<?php echo $row['id']; ?>">Decline</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
