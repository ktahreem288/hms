<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  if ($logged == false) {
       header("Location:../../login.php");
       exit;
  }

  // Fetch staff details if ID is provided
  $staff_data = null;
  if (isset($_GET['id'])) {
      $id = intval($_GET['id']);
      $qry = mysqli_query($con, "SELECT * FROM staff WHERE id = $id");
      if ($qry && mysqli_num_rows($qry) > 0) {
          $staff_data = mysqli_fetch_assoc($qry);
      }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>HMS - Staff Details</title>
    <link rel="stylesheet" href="admin_view_mem.css">
    <link rel="shortcut icon" href="Logo3.jpg">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .home-content {
            padding: 20px;
        }
        .details-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .detail-row {
            margin-bottom: 12px;
            font-size: 16px;
        }
        .detail-row b {
            display: inline-block;
            min-width: 180px;
        }
        .date-box {
            border: 1px solid #ccc;
            padding: 4px 12px;
            border-radius: 4px;
            background-color: #f8f9fa;
            display: inline-block;
            min-width: 160px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i><img src="1.png" alt="logo" width="80px" height="80px"></i>
            <span class="logo_name">HMS</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="../Dashboard/admin.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../Bill/index.php">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="links_name">Maintainence Bill</span>
                <li>
                    <a href="../../manage_notice.php">
                        <i class='bx bx-notification'></i>
                        <span class="links_name">Notice</span>
                    </a>
                </li>
                </a>
            </li>
            <li>
                <a href="../Home_Services/home_ser.php">
                    <i class='bx bxs-user-circle'></i>
                    <span class="links_name">Home Services</span>
                </a>
            </li>
            <li>
                <a href="../Neighbourhood/admin_neigh.php">
                    <i class='bx bx-map'></i>
                    <span class="links_name">Neighbourhood</span>
                </a>
            </li>
            <li>
                <a href="admin_view_mem.php">
                    <i class='bx bx-list-ul'></i>
                    <span class="links_name">Members</span>
                </a>
            </li>
            <li>
                <a href="admin_view_staff.php" class="active">
                    <i class='bx bx-user-pin'></i>
                    <span class="links_name">Staff</span>
                </a>
            </li>
            <li>
                <a href="../Dashboard/wings.php">
                    <i class='bx bx-building-house'></i>
                    <span class="links_name">Apartment Data</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-message'></i>
                    <span class="links_name">Chat Box</span>
                </a>
            </li>
            <li>
                <a href="../Event_calender/admin_event.php">
                    <i class='bx bx-calendar-event'></i>
                    <span class="links_name">Events</span>
                </a>
            </li>
            <li class="log_out">
                <a href="../../logout.php">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Log out</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="Announcement">Staff Details</span>
            </div>
            <div class="profile-details">
                <a href="../../logout.php" class="top-logout" style="display:flex;align-items:center;gap:6px;color:#333;text-decoration:none;font-size:14px;font-weight:500;"><i class="bx bx-log-out"></i> Logout</a>
            </div>
        </nav>

        <div class="home-content">
            <div class="details-container">
                <?php if ($staff_data): ?>
                    <!-- Title and Edit Button Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <h3 class="mb-0"><b>Staff Details (<?php echo htmlspecialchars($staff_data['Name'] ?? $staff_data['name'] ?? $staff_data['username'] ?? 'N/A'); ?>)</b></h3>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm mr-2" onclick="window.history.back();">
                                <i class="fa fa-arrow-left"></i> Back
                            </button>
                            <button class="btn btn-primary btn-sm edit_staff" data-id="<?php echo $staff_data['id']; ?>">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <!-- Details Layout from Sketch -->
                    <div class="detail-row">
                        <b>Name &mdash;</b> <?php echo htmlspecialchars($staff_data['Name'] ?? $staff_data['name'] ?? 'N/A'); ?>
                    </div>

                    <div class="detail-row">
                        <b>mob.no &mdash;</b> <?php echo htmlspecialchars($staff_data['Mobile_number'] ?? $staff_data['mobile'] ?? 'N/A'); ?>
                    </div>

                    <div class="detail-row">
                        <b>Address &mdash;</b> <?php echo htmlspecialchars($staff_data['Address'] ?? 'N/A'); ?>
                    </div>

                    <div class="detail-row">
                        <b>id proof &mdash;</b> 
                        <?php if (!empty($staff_data['id_proof'])): ?>
                            <?php $ext = pathinfo($staff_data['id_proof'], PATHINFO_EXTENSION); ?>
                            <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <a href="assets/uploads/<?php echo $staff_data['id_proof']; ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="fa fa-image"></i> Insert pdf/image
                                </a>
                            <?php else: ?>
                                <a href="assets/uploads/<?php echo $staff_data['id_proof']; ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-file-pdf"></i> Insert pdf/image
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="btn btn-outline-secondary btn-sm disabled">Insert pdf/image</span>
                        <?php endif; ?>
                    </div>

                    <div class="detail-row mt-3">
                        <b>permt. address &mdash;</b> <?php echo htmlspecialchars($staff_data['Permanent_Address'] ?? $staff_data['Address'] ?? 'N/A'); ?>
                    </div>

                    <div class="detail-row">
                        <b>Age &mdash;</b> <?php echo htmlspecialchars($staff_data['Age'] ?? 'N/A'); ?>
                    </div>

                    <div class="detail-row">
                        <b>Gender &mdash;</b> <?php echo htmlspecialchars($staff_data['Gender'] ?? 'N/A'); ?>
                    </div>

                    <div class="detail-row">
                        <b>Salary &mdash;</b> <?php echo !empty($staff_data['Monthly_Salary']) ? number_format($staff_data['Monthly_Salary'], 2) : 'N/A'; ?>
                    </div>

                    <div class="detail-row mt-3">
                        <b>join date &mdash;</b> 
                        <span class="date-box">
                            <?php echo !empty($staff_data['Joining_Date']) ? date('Y-m-d', strtotime($staff_data['Joining_Date'])) : 'YYYY-MM-DD'; ?>
                        </span>
                    </div>

                    <div class="detail-row">
                        <b>last date &mdash;</b> 
                        <span class="date-box">
                            <?php echo !empty($staff_data['Last_Date']) ? date('Y-m-d', strtotime($staff_data['Last_Date'])) : 'YYYY-MM-DD'; ?>
                        </span>
                    </div>

                    <div class="detail-row d-flex align-items-center mt-3">
                        <b class="mr-2">is_active &mdash;</b>
                        <div class="btn-group btn-group-toggle">
                            <label class="btn btn-sm btn-outline-success <?php echo (isset($staff_data['is_active']) && $staff_data['is_active'] == 1) ? 'active' : 'disabled'; ?>">
                                <input type="radio" disabled <?php echo (isset($staff_data['is_active']) && $staff_data['is_active'] == 1) ? 'checked' : ''; ?>> Yes
                            </label>
                            <label class="btn btn-sm btn-outline-danger <?php echo (!isset($staff_data['is_active']) || $staff_data['is_active'] == 0) ? 'active' : 'disabled'; ?>">
                                <input type="radio" disabled <?php echo (!isset($staff_data['is_active']) || $staff_data['is_active'] == 0) ? 'checked' : ''; ?>> NO
                            </label>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="text-center py-5">
                        <h4>No staff record selected or found.</h4>
                        <a href="staff/stafflist.php" class="btn btn-primary mt-3">Go to Staff List</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else {
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
            }
        }

        $('.edit_staff').click(function(){
            debugger;
            let id = $(this).attr('data-id');
            window.location.href = 'staff/manage_staff.php?id=' + id;
        });
    </script>
</body>
</html>