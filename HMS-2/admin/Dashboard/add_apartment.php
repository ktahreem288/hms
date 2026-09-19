<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  if ($logged == false) {
       header("Location:../../login.php");
       exit;
  }

    $selected_wing = isset($_GET['wing']) ? strtoupper(substr(trim($_GET['wing']), 0, 1)) : 'A';
    $form_error = '';

  // Handle Form Submission
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wing = strtoupper(substr(trim($_POST['wing'] ?? ''), 0, 1));
    $flat_number = trim($_POST['flat_number'] ?? '');
    $owner_name = trim($_POST['owner_name'] ?? '');
    $contact_no = trim($_POST['contact_no'] ?? '');
      $current_accommodation = $_POST['current_accommodation'];
      
      $tenant_name = isset($_POST['tenant_name']) ? $_POST['tenant_name'] : '';
      $tenant_contact = isset($_POST['tenant_contact']) ? $_POST['tenant_contact'] : '';
      $tenant_start_date = isset($_POST['tenant_start_date']) ? $_POST['tenant_start_date'] : '';
      $tenant_end_date = isset($_POST['tenant_end_date']) ? $_POST['tenant_end_date'] : '';
      
      $owner_start_date = $_POST['owner_start_date'];
      $parking_allotted = $_POST['parking_allotted'];

      $flat_id = $wing . '-' . $flat_number;
      $username = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '.', $flat_id));
      $email = $username . '@hms.local';
      $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
      $member_role = 0;
    $is_active = isset($_POST['is_active']) && (int)$_POST['is_active'] === 1 ? 1 : 0;

      if ($wing === '' || $flat_number === '' || $owner_name === '' || $contact_no === '') {
          $form_error = 'Please complete the wing, flat number, owner name, and contact number.';
      } else {
          $stmt = mysqli_prepare($con, "INSERT INTO member (name, username, email, password, flat_id, phone, member_role, is_active, current_accommodation, tenant_name, tenant_contact, tenant_start_date, tenant_end_date, owner_start_date, parking_allotted, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''), ?, NOW())");
          if ($stmt) {
              mysqli_stmt_bind_param($stmt, 'ssssssiisssssss', $owner_name, $username, $email, $password, $flat_id, $contact_no, $member_role, $is_active, $current_accommodation, $tenant_name, $tenant_contact, $tenant_start_date, $tenant_end_date, $owner_start_date, $parking_allotted);
              if (mysqli_stmt_execute($stmt)) {
                  mysqli_stmt_close($stmt);
                  if (isset($_POST['modal_submit'])) {
                      echo '1';
                      exit;
                  }
                  header('Location: view_wing.php?wing=' . urlencode($wing));
                  exit;
              }
              $form_error = 'Unable to save apartment data: ' . mysqli_stmt_error($stmt);
              mysqli_stmt_close($stmt);
          } else {
              $form_error = 'Unable to prepare the apartment data record: ' . mysqli_error($con);
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Add / Update Apartment Data - HMS</title>
    <link rel="stylesheet" href="admin.css?v=2">
    <link rel="shortcut icon" href="Logo3.jpg">

    <!-- Boxicons & Bootstrap CSS -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .form-card {
            background: #fff;
            padding: 28px 32px;
            border-radius: 8px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.08);
            max-width: 900px;
            margin: 20px auto;
        }

        .form-card .form-group {
            margin-bottom: 1rem;
        }

        .form-card label {
            color: #444;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .form-card .form-control,
        .form-card .form-select {
            min-height: 42px;
            border-color: #d5dbe1;
            border-radius: 4px;
        }

        .form-card .form-control:focus,
        .form-card .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .15);
        }

        .form-card .form-heading {
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .form-card .form-heading h3 {
            margin-bottom: 4px;
        }

        .form-card .form-heading p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        .tenant-box {
            border: 1px solid #d5dbe1;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
            margin-top: 15px;
            margin-bottom: 20px;
            display: none; /* Hidden by default */
        }

        .btn-custom-add {
            background: #0A2540;
            color: #fff;
            padding: 8px 30px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
        }

        .btn-custom-add:hover {
            background: #163a5f;
            color: #fff;
        }

        .btn-custom-cancel {
            background: #6c757d;
            color: #fff;
            padding: 8px 30px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
        }

        .btn-custom-cancel:hover {
            background: #5a6268;
            color: #fff;
        }

        .apartment-switch .custom-control-label::before {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .apartment-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        .apartment-switch {
            padding-left: 2.25rem;
        }

        .apartment-switch .custom-control-input {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        .apartment-switch .custom-control-label {
            position: relative;
            margin-bottom: 0;
            vertical-align: top;
        }

        .apartment-switch .custom-control-label::before,
        .apartment-switch .custom-control-label::after {
            position: absolute;
            display: block;
            content: "";
        }

        .apartment-switch .custom-control-label::before {
            top: 0.25rem;
            left: -2.25rem;
            width: 1.75rem;
            height: 1rem;
            border-radius: 1rem;
            transition: background-color 0.15s ease-in-out;
        }

        .apartment-switch .custom-control-label::after {
            top: 0.375rem;
            left: -2rem;
            width: 0.75rem;
            height: 0.75rem;
            background-color: #fff;
            border-radius: 50%;
            transition: transform 0.15s ease-in-out;
        }

        .apartment-switch .custom-control-input:checked ~ .custom-control-label::after {
            transform: translateX(0.75rem);
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-details">
            <i><img src="1.png" alt="logo" width="100px" height="100px"></i>
            <span class="logo_name">HMS</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="admin.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../Bill/index.php">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="links_name">Maintainence Bill</span>
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
                <a href="../Members/index.php">
                    <i class='bx bx-list-ul'></i>
                    <span class="links_name">Members</span>
                </a>
            </li>
            <li>
                <a href="../Members/staff/index.php">
                    <i class='bx bx-list-ul'></i>
                    <span class="links_name">Staff</span>
                </a>
            </li>
            <li>
                <a href="../manage_notice.php">
                    <i class='bx bx-notification'></i>
                    <span class="links_name">Notice</span>
                </a>
            </li>
            <li>
                <a href="wings.php" class="active">
                    <i class='bx bx-building-house'></i>
                    <span class="links_name">Apartment Data</span>
                </a>
            </li>
            <li>
                <a href="https://discord.gg/ytmJWCjyHZ" target="_blank">
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

    <!-- Main Content -->
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Apartment Data</span>
            </div>
            
            <div class="profile-details">
                <a href="../../logout.php" class="top-logout" style="display:flex;align-items:center;gap:6px;color:#333;text-decoration:none;font-size:14px;font-weight:500;"><i class="bx bx-log-out"></i> Logout</a>
            </div>
        </nav>

        <div class="home-content">
            <div class="form-card">
                <div class="form-heading">
                    <h3 class="fw-bold">Add Apartment Details</h3>
                    <p>Enter apartment and resident information below.</p>
                </div>

                <?php if ($form_error !== ''): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($form_error); ?></div>
                <?php endif; ?>

                <form action="add_apartment.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 form-group">
                        <label for="wing" class="fw-bold">Wing</label>
                        <div>
                            <select name="wing" id="wing" class="form-select" required>
                                <?php 
                                $wings = range('A', 'J');
                                foreach ($wings as $w): 
                                ?>
                                    <option value="<?php echo $w; ?>" <?php echo ($selected_wing == $w) ? 'selected' : ''; ?>>
                                        <?php echo $w; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>

                        <div class="col-md-6 form-group">
                        <label for="flat_number" class="fw-bold">Flat No.</label>
                        <input type="text" name="flat_number" id="flat_number" class="form-control" required placeholder="Example: 101">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="owner_name" class="fw-bold">Owner Name</label>
                            <input type="text" name="owner_name" id="owner_name" class="form-control" required placeholder="Enter owner name">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="contact_no" class="fw-bold">Contact No.</label>
                            <input type="text" name="contact_no" id="contact_no" class="form-control" required placeholder="Enter contact number">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="current_accommodation" class="fw-bold">Current Accommodation</label>
                        <select name="current_accommodation" id="current_accommodation" class="form-select" onchange="toggleTenantSection()">
                                <option value="Owner">Owner</option>
                                <option value="Tenant">Tenant</option>
                            </select>
                    </div>

                    <div id="tenant_section" class="tenant-box">
                        <h5 class="fw-bold mb-3 text-primary">Tenant Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="tenant_name" class="fw-bold">Tenant Name</label>
                                <input type="text" name="tenant_name" id="tenant_name" class="form-control" placeholder="Enter tenant name">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="tenant_contact" class="fw-bold">Tenant Contact No.</label>
                                <input type="text" name="tenant_contact" id="tenant_contact" class="form-control" placeholder="Enter tenant contact">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="tenant_start_date" class="fw-bold">Tenant Start Date</label>
                                <input type="date" name="tenant_start_date" id="tenant_start_date" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="tenant_end_date" class="fw-bold">Tenant End Date</label>
                                <input type="date" name="tenant_end_date" id="tenant_end_date" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="rent_agreement" class="fw-bold">Rent Agreement</label>
                                <input type="file" name="rent_agreement" id="rent_agreement" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="police_verification" class="fw-bold">Police Verification</label>
                                <input type="file" name="police_verification" id="police_verification" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="owner_start_date" class="fw-bold">Owner Accommodation Start Date</label>
                            <input type="date" name="owner_start_date" id="owner_start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="parking_allotted" class="fw-bold">Parking Allotted</label>
                            <select name="parking_allotted" id="parking_allotted" class="form-select">
                                <option value="YES">YES</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="noc_issued" class="fw-bold">NOC Issued</label>
                        <input type="file" name="noc_issued" id="noc_issued" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="fw-bold d-block" for="add_is_active">Is Active</label>
                        <div class="custom-control custom-switch apartment-switch mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="custom-control-input" name="is_active" id="add_is_active" value="1" checked>
                            <label class="custom-control-label" for="add_is_active" id="add_active_status_label">Yes</label>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="text-center gap-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                        <a href="view_wing.php?wing=<?php echo $selected_wing; ?>" class="btn btn-secondary btn-sm">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Toggle Tenant Section based on Accommodation Selection
        function toggleTenantSection() {
            var accommodationType = document.getElementById("current_accommodation").value;
            var tenantBox = document.getElementById("tenant_section");
            if (accommodationType === "Tenant") {
                tenantBox.style.display = "block";
            } else {
                tenantBox.style.display = "none";
            }
        }

        document.getElementById("add_is_active").addEventListener("change", function () {
            document.getElementById("add_active_status_label").textContent = this.checked ? "Yes" : "No";
        });

        // Sidebar logic
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
    </script>
</body>
</html>