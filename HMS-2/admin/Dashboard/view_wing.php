<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  if ($logged == false) {
       header("Location:../../login.php");
       exit;
  }

  $wing = isset($_GET['wing']) ? htmlspecialchars($_GET['wing']) : 'A';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Manage <?php echo $wing; ?> Wing Data - HMS</title>
    <link rel="stylesheet" href="admin.css?v=2">
    <link rel="shortcut icon" href="Logo3.jpg">

    <!-- Boxicons & Bootstrap CSS -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .table-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        .btn-back {
            text-decoration: none;
            background: #0A2540;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s ease;
        }

        .btn-back:hover {
            background: #163a5f;
            color: #fff;
        }

        .btn-add-apartment {
            text-decoration: none;
            background: #0A2540;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s ease;
        }

        .btn-add-apartment:hover {
            background: #163a5f;
            color: #fff;
        }

        #apartment_modal .form-card {
            max-width: none;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }

        #apartment_modal .form-heading {
            display: none;
        }

        #apartment_modal .form-card label {
            font-weight: 600;
        }

        #apartment_modal .apartment-switch {
            padding-left: 2.25rem;
        }

        #apartment_modal .apartment-switch .custom-control-input {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        #apartment_modal .apartment-switch .custom-control-label {
            position: relative;
            display: inline-block;
            margin-bottom: 0;
            vertical-align: top;
        }

        #apartment_modal .apartment-switch .custom-control-label::before,
        #apartment_modal .apartment-switch .custom-control-label::after {
            position: absolute;
            display: block;
            content: "";
        }

        #apartment_modal .apartment-switch .custom-control-label::before {
            top: 0.25rem;
            left: -2.25rem;
            width: 1.75rem;
            height: 1rem;
            border-radius: 1rem;
            background-color: #dc3545;
            border: 1px solid #dc3545;
        }

        #apartment_modal .apartment-switch .custom-control-label::after {
            top: 0.375rem;
            left: -2rem;
            width: 0.75rem;
            height: 0.75rem;
            background-color: #fff;
            border-radius: 50%;
            transition: transform 0.15s ease-in-out;
        }

        #apartment_modal .apartment-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #28a745;
            border-color: #28a745;
        }

        #apartment_modal .apartment-switch .custom-control-input:checked ~ .custom-control-label::after {
            transform: translateX(0.75rem);
        }

        .sidebar .nav-links {
            display: flex;
            flex-direction: column;
            height: auto;
            min-height: 0;
            margin-top: 10px;
            padding: 0;
            gap: 0;
        }

        .sidebar .nav-links li {
            margin: 0;
        }

        .sidebar .nav-links .log_out {
            margin-top: 0;
            width: 100%;
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
            <div class="management-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="fw-bold mb-1">Manage <?php echo $wing; ?> Wing Data</h2>
                        <p class="text-muted mb-0">List of registered flats and residence details in <?php echo $wing; ?> Wing</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm apartment-modal-link" data-title="Add Apartment Details" data-url="add_apartment.php?wing=<?php echo $wing; ?>&modal=1" data-show-submit="true" data-form-type="add">
                        <i class='bx bx-plus'></i> Add Apartment
                    </button>
                </div>

                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Flat ID</th>
                                    <th>Contact No</th>
                                    <th>Owner</th>
                                    <th>Maintenance Due</th>
                                    <th class="text-center">Update / View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $con = new mysqli('127.0.0.1:3307', 'root', '', 'hms');
                                
                                // Fetch residents belonging to the selected wing
                                $query = "SELECT m.id, m.name as owner_name, m.phone, m.flat_id, 
                                                 COALESCE(SUM(b.amount_payed), 0) as maintenance_due
                                          FROM member m 
                                          LEFT JOIN billing b ON m.id = b.member_id AND b.status = 0
                                          WHERE m.flat_id LIKE '{$wing}-%' OR m.flat_id LIKE '{$wing}%'
                                          GROUP BY m.id";
                                          
                                $result = mysqli_query($con, $query);

                                if ($result && mysqli_num_rows($result) > 0):
                                    while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['flat_id']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($row['owner_name']); ?></td>
                                        <td>
                                            <?php if ($row['maintenance_due'] > 0): ?>
                                                <span class="badge bg-danger">₹<?php echo number_format($row['maintenance_due'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-info btn-sm apartment-modal-link" data-title="View Apartment Details" data-url="../Members/view_apartment.php?id=<?php echo $row['id']; ?>" data-show-submit="false">
                                                View
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm apartment-modal-link" data-title="Edit Apartment Details" data-url="../Members/manage_user.php?id=<?php echo $row['id']; ?>&is_apartment=1&from=apartment" data-show-submit="true">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php 
                                    endwhile;
                                else: 
                                ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No records found for <?php echo $wing; ?> Wing.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Back to Wings Button below Table -->
                <div class="mt-4">
                    <a href="wings.php" class="btn btn-secondary btn-sm">
                        <i class='bx bx-left-arrow-alt'></i> Back to Wings
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="apartment_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="apartment_modal_submit">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.apartment-modal-link').forEach(function (button) {
            button.addEventListener('click', function () {
                var modalElement = document.getElementById('apartment_modal');
                var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                var submitButton = document.getElementById('apartment_modal_submit');
                var body = document.querySelector('#apartment_modal .modal-body');

                document.querySelector('#apartment_modal .modal-title').textContent = button.dataset.title;
                submitButton.style.display = button.dataset.showSubmit === 'true' ? '' : 'none';
                body.innerHTML = '<div class="text-center py-4">Loading...</div>';
                modal.show();

                fetch(button.dataset.url)
                    .then(function (response) { return response.text(); })
                    .then(function (html) {
                        body.innerHTML = html;
                        if (button.dataset.formType === 'add') {
                            var page = new DOMParser().parseFromString(html, 'text/html');
                            var apartmentCard = page.querySelector('.form-card');
                            body.innerHTML = apartmentCard ? apartmentCard.outerHTML : html;
                        }
                        var formActions = body.querySelector('#apartmentFormActions, #apartmentViewActions');
                        if (formActions) formActions.remove();
                        var embeddedActions = body.querySelector('.text-center.gap-3');
                        if (embeddedActions) embeddedActions.remove();
                        var form = body.querySelector('form');
                        var accommodation = body.querySelector('#current_accommodation');
                        var tenantSection = body.querySelector('#tenant_section');

                        if (accommodation && tenantSection) {
                            var updateTenantSection = function () {
                                tenantSection.style.display = accommodation.value === 'Tenant' ? 'block' : 'none';
                            };
                            accommodation.addEventListener('change', updateTenantSection);
                            updateTenantSection();
                        }

                        if (form && button.dataset.showSubmit === 'true') {
                            form.addEventListener('submit', function (event) {
                                event.preventDefault();
                                submitButton.disabled = true;
                                var requestUrl = button.dataset.formType === 'add' ? 'add_apartment.php?modal=1' : '../Members/ajax.php?action=save_user';
                                var formData = new FormData(form);
                                if (button.dataset.formType === 'add') formData.append('modal_submit', '1');
                                fetch(requestUrl, {
                                    method: 'POST',
                                    body: button.dataset.formType === 'add' ? formData : new URLSearchParams(formData)
                                })
                                    .then(function (response) { return response.text(); })
                                    .then(function (result) {
                                        if (result.trim() === '1') {
                                            window.location.reload();
                                        } else {
                                            alert('Save failed: ' + (result.trim() || 'The server returned an empty response.'));
                                            submitButton.disabled = false;
                                        }
                                    })
                                    .catch(function () {
                                        alert('Save failed. Please try again.');
                                        submitButton.disabled = false;
                                    });
                            });
                        }

                        if (button.dataset.showSubmit === 'true') {
                            submitButton.onclick = function () {
                                var editForm = body.querySelector('form');
                                if (editForm) editForm.requestSubmit();
                            };
                        }
                    })
                    .catch(function () {
                        body.innerHTML = '<div class="alert alert-danger">Unable to load apartment details.</div>';
                    });
            });
        });

        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        if (sidebar && sidebarBtn) {
            sidebarBtn.onclick = function () {
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
                } else {
                    sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
                }
            }
        }
    </script>
</body>
</html>