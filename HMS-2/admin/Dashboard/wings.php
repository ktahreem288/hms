<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  if ($logged == false) {
       header("Location:../../login.php");
       exit;
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Manage Wings - HMS</title>
    <link rel="stylesheet" href="admin.css?v=2">
    <link rel="shortcut icon" href="Logo3.jpg">

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .wings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .wing-card {
            background: #fff;
            padding: 25px 20px;
            border-radius: 12px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .wing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.12);
            border-color: #0A2540;
            color: #0A2540;
        }

        .wing-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .wing-info i {
            font-size: 28px;
            background: #eef2f5;
            color: #0A2540;
            padding: 12px;
            border-radius: 8px;
        }

        .wing-name {
            font-size: 18px;
            font-weight: 600;
        }

        .wing-arrow {
            font-size: 24px;
            color: #888;
        }

        .wing-card:hover .wing-arrow {
            color: #0A2540;
        }

        .btn-add-apartment {
            text-decoration: none;
            background: #0A2540;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s ease;
        }

        .btn-add-apartment:hover {
            background: #163a5f;
            color: #fff;
        }

        .btn-back-dashboard {
            text-decoration: none;
            background: #0A2540;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s ease;
        }

        .btn-back-dashboard:hover {
            background: #163a5f;
            color: #fff;
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

    <!-- Main Section -->
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
                <div class="management-heading" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2>Select Apartment Wing</h2>
                        <p style="color: #666; font-size: 14px; margin-top: 5px;">Choose a wing to manage residence data</p>
                    </div>
                    <a href="add_apartment.php" class="btn btn-primary btn-sm">
                        <i class='bx bx-plus'></i> Add New Apartment
                    </a>
                </div>

                <!-- Wings Grid -->
                <div class="wings-grid">
                    <?php 
                    $wings = range('A', 'J');
                    foreach ($wings as $wing): 
                    ?>
                        <a href="view_wing.php?wing=<?php echo $wing; ?>" class="wing-card">
                            <div class="wing-info">
                                <i class='bx bx-building-house'></i>
                                <span class="wing-name"><?php echo $wing; ?> Wing</span>
                            </div>
                            <i class='bx bx-chevron-right wing-arrow'></i>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Back to Dashboard Button below Wings -->
                <div style="margin-top: 30px;">
                    <a href="admin.php" class="btn btn-secondary btn-sm">
                        <i class='bx bx-left-arrow-alt' style="font-size: 18px;"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
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