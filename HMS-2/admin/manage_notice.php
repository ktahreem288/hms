<?php
require_once("../Includes/config.php");
require_once("../Includes/session.php");

if ($logged == false) {
    header("Location:../login.php");
    exit;
}

// Handle Form Submissions (Add / Update)
$message = '';
$error = '';

// 1. ADD NOTICE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_notice'])) {
    $topic = trim($_POST['topic'] ?? '');
    $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
    $image_name = '';

    if (!empty($_FILES['notice_image']['name'])) {
        $target_dir = "uploads/notices/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES['notice_image']['name']);
        move_uploaded_file($_FILES['notice_image']['tmp_name'], $target_dir . $image_name);
    }

    if (!empty($topic)) {
        $stmt = mysqli_prepare($con, "INSERT INTO notices (topic, image, is_active, created_at) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "ssi", $topic, $image_name, $is_active);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: manage_notice.php?msg=added");
            exit;
        } else {
            $error = "Error adding notice: " . mysqli_error($con);
        }
    } else {
        $error = "Topic is required.";
    }
}

// 2. UPDATE NOTICE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_notice'])) {
    $id = intval($_POST['notice_id']);
    $topic = trim($_POST['topic'] ?? '');
    $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;

    if (!empty($_FILES['notice_image']['name'])) {
        $target_dir = "uploads/notices/";
        $image_name = time() . '_' . basename($_FILES['notice_image']['name']);
        move_uploaded_file($_FILES['notice_image']['tmp_name'], $target_dir . $image_name);
        
        $stmt = mysqli_prepare($con, "UPDATE notices SET topic=?, image=?, is_active=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssii", $topic, $image_name, $is_active, $id);
    } else {
        $stmt = mysqli_prepare($con, "UPDATE notices SET topic=?, is_active=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sii", $topic, $is_active, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        header("Location: manage_notice.php?msg=updated");
        exit;
    } else {
        $error = "Error updating notice.";
    }
}

// Fetch all notices
$query = "SELECT * FROM notices ORDER BY id DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notice</title>
    <link rel="stylesheet" href="Dashboard/admin.css?v=2">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .custom-card { border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .switch-toggle { display: flex; align-items: center; gap: 10px; }
        .home-content { padding: 24px; }
        .notice-container { margin: 0; }
        .notice-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #6c757d;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .notice-logout:hover {
            background: #5a6268;
            color: #fff;
            text-decoration: none;
        }
        .notice-switch {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .notice-switch input[type="checkbox"] {
            position: relative;
            width: 34px;
            height: 18px;
            margin: 0;
            appearance: none;
            -webkit-appearance: none;
            background: #dc3545;
            border: 1px solid #dc3545;
            border-radius: 18px;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
        }
        .notice-switch input[type="checkbox"]::after {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 12px;
            height: 12px;
            content: "";
            background: #fff;
            border-radius: 50%;
            transition: transform 0.15s ease-in-out;
        }
        .notice-switch input[type="checkbox"]:checked {
            background: #28a745;
            border-color: #28a745;
        }
        .notice-switch input[type="checkbox"]:checked::after {
            transform: translateX(16px);
        }
        .notice-page .sidebar {
            z-index: 1000;
        }
        .notice-page .home-section {
            z-index: 1;
        }
        @media (min-width: 701px) and (max-width: 1240px) {
            .notice-page .sidebar,
            .notice-page .sidebar.active {
                width: 240px;
            }
            .notice-page .home-section,
            .notice-page .sidebar.active ~ .home-section {
                width: calc(100% - 240px);
                left: 240px;
            }
            .notice-page .home-section nav,
            .notice-page .sidebar.active ~ .home-section nav {
                width: calc(100% - 240px);
                left: 240px;
            }
        }
    </style>
</head>
<body class="notice-page">

<div class="sidebar">
    <div class="logo-details">
        <i><img src="Dashboard/1.png" alt="logo" width="100" height="100"></i>
        <span class="logo_name">HMS</span>
    </div>
    <ul class="nav-links">
        <li><a href="Dashboard/admin.php"><i class="bx bx-grid-alt"></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="Bill/index.php"><i class="bx bx-pie-chart-alt-2"></i><span class="links_name">Maintainence Bill</span></a></li>
        <li><a href="Home_Services/home_ser.php"><i class="bx bxs-user-circle"></i><span class="links_name">Home Services</span></a></li>
        <li><a href="Neighbourhood/admin_neigh.php"><i class="bx bx-map"></i><span class="links_name">Neighbourhood</span></a></li>
        <li><a href="Members/index.php"><i class="bx bx-list-ul"></i><span class="links_name">Members</span></a></li>
        <li><a href="Members/staff/index.php"><i class="bx bx-list-ul"></i><span class="links_name">Staff</span></a></li>
        <li><a href="manage_notice.php" class="active"><i class="bx bx-notification"></i><span class="links_name">Notice</span></a></li>
        <li><a href="Dashboard/wings.php"><i class="bx bx-building-house"></i><span class="links_name">Apartment Data</span></a></li>
        <li><a href="https://discord.gg/ytmJWCjyHZ" target="_blank"><i class="bx bx-message"></i><span class="links_name">Chat Box</span></a></li>
        <li><a href="Event_calender/admin_event.php"><i class="bx bx-calendar-event"></i><span class="links_name">Events</span></a></li>
        <li class="log_out"><a href="../logout.php"><i class="bx bx-log-out"></i><span class="links_name">Log out</span></a></li>
    </ul>
</div>

<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Notice</span>
        </div>
        <div class="profile-details">
            <a href="../logout.php" class="notice-logout">
                <i class="bx bx-log-out"></i> Logout
            </a>
        </div>
    </nav>
    <div class="home-content">

<div class="container-fluid bg-white p-4 rounded shadow-sm notice-container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Notice</h2>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addNoticeModal">+ Add Notice</button>
        </div>
    </div>

    <!-- Notices Table -->
    <table class="table table-bordered align-middle">
        <thead class="thead-light">
            <tr>
                <th width="5%">ID</th>
                <th>Topic</th>
                <th width="18%">Date of Notice</th>
                <th width="18%">Inserted By</th>
                <th width="15%">Is Active / Inactive</th>
                <th width="20%">View / Update</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['topic']); ?></td>
                        <td><?php echo date('d/m/y', strtotime($row['created_at'])); ?></td>
                        <td><?php echo htmlspecialchars($row['created_by'] ?? 'Admin'); ?></td>
                        <td>
                            <?php echo $row['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group-vertical btn-group-sm w-100">
                            <button class="btn btn-outline-info btn-sm view-btn" 
                                data-id="<?php echo $row['id']; ?>"
                                data-topic="<?php echo htmlspecialchars($row['topic']); ?>"
                                data-date="<?php echo date('d/m/y', strtotime($row['created_at'])); ?>"
                                data-inserted-by="<?php echo htmlspecialchars($row['created_by'] ?? 'Admin'); ?>"
                                data-active="<?php echo $row['is_active']; ?>"
                                data-image="<?php echo htmlspecialchars($row['image']); ?>">View</button>
                            
                            <button class="btn btn-outline-primary btn-sm edit-btn" 
                                data-id="<?php echo $row['id']; ?>"
                                data-topic="<?php echo htmlspecialchars($row['topic']); ?>"
                                data-date="<?php echo date('d/m/y', strtotime($row['created_at'])); ?>"
                                data-inserted-by="<?php echo htmlspecialchars($row['created_by'] ?? 'Admin'); ?>"
                                data-active="<?php echo $row['is_active']; ?>"
                                data-image="<?php echo htmlspecialchars($row['image']); ?>">Update</button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No notices found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-3 text-left">
        <a href="Dashboard/admin.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

    </div>
</section>

<!-- ================= 1. ADD NOTICE MODAL ================= -->
<div class="modal fade" id="addNoticeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add Notice</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Topic :</label>
                        <input type="text" name="topic" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Date of upload</label>
                        <input type="text" class="form-control" value="<?php echo date('d/m/y'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Insert Image</label>
                        <input type="file" name="notice_image" class="form-control-file">
                    </div>
                    <div class="form-group">
                        <label>Is Active</label>
                        <div class="notice-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="add_active_switch" name="is_active" value="1" checked>
                            <span id="add_active_label">YES</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_notice" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= 2. VIEW NOTICE MODAL ================= -->
<div class="modal fade" id="viewNoticeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Notice</h5>
            </div>
            <div class="modal-body">
                <p><strong>Topic :</strong> <span id="v_topic"></span></p>
                <p><strong>Date of upload :</strong> <span id="v_date"></span></p>
                <div class="form-group">
                    <label><strong>Uploaded Image :</strong></label>
                    <div class="border p-2 text-center rounded bg-light">
                        <img id="v_image" src="" alt="Notice Image" class="img-fluid" style="max-height: 200px; display:none;">
                        <span id="v_no_image" class="text-muted">No Image Uploaded</span>
                    </div>
                </div>
                <div class="form-group">
                    <label><strong>Is Active</strong></label>
                    <div class="notice-switch">
                        <input type="checkbox" id="v_active_switch" disabled>
                        <span id="v_active_label">NO</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ================= 3. UPDATE NOTICE MODAL ================= -->
<div class="modal fade" id="updateNoticeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Update Notice</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="notice_id" id="u_id">
                    <div class="form-group">
                        <label>Topic :</label>
                        <input type="text" name="topic" id="u_topic" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Date of upload</label>
                        <input type="text" id="u_date" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Insert updated notice</label>
                        <input type="file" name="notice_image" class="form-control-file">
                        <small class="form-text text-muted">Leave blank to keep existing image</small>
                    </div>
                    <div class="form-group">
                        <label>Is Active</label>
                        <div class="notice-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="update_active_switch" name="is_active" value="1">
                            <span id="update_active_label">NO</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_notice" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#add_active_switch').on('change', function() {
        $('#add_active_label').text(this.checked ? 'YES' : 'NO');
    });

    $('#update_active_switch').on('change', function() {
        $('#update_active_label').text(this.checked ? 'YES' : 'NO');
    });

    // Populate View Modal
    $('.view-btn').click(function() {
        var topic = $(this).data('topic');
        var date = $(this).data('date');
        var active = $(this).data('active');
        var image = $(this).data('image');

        $('#v_topic').text(topic);
        $('#v_date').text(date);
        
        if(image != '') {
            $('#v_image').attr('src', 'uploads/notices/' + image).show();
            $('#v_no_image').hide();
        } else {
            $('#v_image').hide();
            $('#v_no_image').show();
        }

        $('#v_active_switch').prop('checked', active == 1);
        $('#v_active_label').text(active == 1 ? 'YES' : 'NO');

        $('#viewNoticeModal').modal('show');
    });

    // Populate Update Modal
    $('.edit-btn').click(function() {
        var id = $(this).data('id');
        var topic = $(this).data('topic');
        var date = $(this).data('date');
        var active = $(this).data('active');

        $('#u_id').val(id);
        $('#u_topic').val(topic);
        $('#u_date').val(date);
        $('#update_active_switch').prop('checked', active == 1);
        $('#update_active_label').text(active == 1 ? 'YES' : 'NO');

        $('#updateNoticeModal').modal('show');
    });
});
</script>

<script>
    var sidebar = document.querySelector('.sidebar');
    var sidebarBtn = document.querySelector('.sidebarBtn');
    if (sidebar && sidebarBtn) {
        sidebarBtn.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    }
</script>

</body>
</html>