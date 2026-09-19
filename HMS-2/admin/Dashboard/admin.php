<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  if ($logged==false) {
       header("Location:../../login.php");
  }
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="admin.css?v=2">
    <link rel="shortcut icon" href="Logo3.jpg">

    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i><img src="1.png"
                 alt="logo" width="100px" height="100px"></i>
            <span class="logo_name">HMS</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="admin.php" class="active">
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
                <a href="wings.php">
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
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Dashboard</span>
            </div>
            
            <div class="profile-details">
               
                <a href="../../logout.php" class="top-logout" style="display:flex;align-items:center;gap:6px;color:#333;text-decoration:none;font-size:14px;font-weight:500;"><i class="bx bx-log-out"></i> Logout</a>
                
            </div>
        </nav>

        <div class="home-content">
            <div class="overview-boxes">
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Total members</div>
                       
                        <?php
                            $con = new mysqli('127.0.0.1:3307', 'root', '', 'hms');
                            $sql = "SELECT * FROM member";
                            if ($result=mysqli_query($con,$sql)) {
                                $rowcount=mysqli_num_rows($result);
                                // echo $rowcount; 
                            }
                        ?>  
                        <div class="number"><?php echo $rowcount ?></div>
                        </div>
                    <i class='bx bxs-user icon member'></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Total Staff</div>

                        <?php
                            $con = new mysqli('127.0.0.1:3307', 'root', '', 'hms');
                            $sql = "SELECT * FROM staff";
                            if ($result=mysqli_query($con,$sql)) {
                                $rowcount=mysqli_num_rows($result);
                                // echo $rowcount; 
                            }
                        ?>  
                        <div class="number"><?php echo $rowcount ?></div>
                    </div>
                    <i class='bx bxs-user-circle  icon staff'></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Society fund</div>
                        <?php
                            $con = new mysqli('127.0.0.1:3307', 'root', '', 'hms');
                            $query="select SUM(amount_payed) as `societyfund` from billing";
                            $res=mysqli_query($con, $query);
                            $data=mysqli_fetch_array($res);
                        ?> 
                        <div class="number"><?php echo $data['societyfund'] ?></div>

                    </div>
                    <i class='bx bx-money icon money'></i>
                </div>
                <div class="box">
                    <div class="right-side">
                        <div class="box-topic">Unpaid Bills</div>
                        <?php
                            $con = new mysqli('127.0.0.1:3307', 'root', '', 'hms');
                            $sql = "SELECT * FROM billing where status=0";
                            if ($result=mysqli_query($con,$sql)) {
                                $rowcount=mysqli_num_rows($result);
                                // echo $rowcount; 
                            }
                        ?> 
                        <div class="number"><?php echo $rowcount ?></div>
                    </div>
                    <i class='bx bxs-file icon file'></i>
                </div>
            </div>

            <div class="management-box">
                <div class="management-heading">
                    <div>
                        <h2>Quick Management</h2>
                    </div>
                </div>
                <div class="management-actions">
                    <a class="management-action" href="../Members/index.php?from_dashboard=1">
                        <i class='bx bx-group'></i>
                        <span><strong>Manage Members</strong><small>Committee Members and Profiles</small></span>
                        <i class='bx bx-chevron-right action-arrow'></i>
                    </a>
                    <a class="management-action" href="../Members/staff/index.php?from_dashboard=1">
                        <i class='bx bx-id-card'></i>
                        <span><strong>Manage Staff</strong><small>Contractors and staff records</small></span>
                        <i class='bx bx-chevron-right action-arrow'></i>
                    </a>
                    <a class="management-action" href="wings.php">
                        <i class='bx bx-building-house'></i>
                        <span><strong>Manage Apartment Data</strong><small>Residence Information</small></span>
                        <i class='bx bx-chevron-right action-arrow'></i>
                    </a>
                    <a class="management-action" href="../Bill/index.php">
                        <i class='bx bx-receipt'></i>
                        <span><strong>Manage Maintenance</strong><small>Maintenance billing</small></span>
                        <i class='bx bx-chevron-right action-arrow'></i>
                    </a>
                    <a class="management-action" href="../Bill/index.php">
                        <i class='bx bx-wallet'></i>
                        <span><strong>Manage Expenses</strong><small>Society payments and expenses</small></span>
                        <i class='bx bx-chevron-right action-arrow'></i>
                    </a>
                </div>
            </div>
            
        </div>
    </section>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    </script>

</body>

</html>