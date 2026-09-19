<?php 
  require_once("../../Includes/config.php"); 
  require_once("../../Includes/session.php"); 
  if ($logged == false) {
       header("Location:../../login.php");
       exit;
  }
  include('header.php'); 
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="billing.css">
    <link rel="stylesheet" href="admin_view_mem.css">
    <link rel="shortcut icon" href="Logo3.jpg">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <a href="staff/index.php">
                    <i class='bx bx-list-ul'></i>
                    <span class="links_name">Staff</span>
                </a>
            </li>
            <li>
                <a href="../../manage_notice.php">
                    <i class='bx bx-notification'></i>
                    <span class="links_name">Notice</span>
                </a>
            </li>
            <li>
                <a href="../Dashboard/wings.php">
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
                    <i class="bx bx-menu sidebarBtn"></i>
                    <span class="dashboard">Members</span>
                </div>
                <div class="profile-details">
                    <a href="../../logout.php" class="top-logout" style="display:flex;align-items:center;gap:6px;color:#333;text-decoration:none;font-size:14px;font-weight:500;"><i class="bx bx-log-out"></i> Logout</a>
                </div>
            </nav>
      <div id="view-panel">
          <?php 
                        $page = isset($_GET['page']) ? $_GET['page'] : 'memberlist'; 
            
            if(file_exists($page.'.php')){
                include $page.'.php';
            } else {
                echo "<div class='alert alert-danger'>Page not found.</div>";
            }
          ?>
      </div>
    </section>

    <!-- Modals -->
    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
              <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
    </div>

    <script>
      window.start_load = function(){
        $('body').prepend('<div id="preloader2"></div>')
      }
      window.end_load = function(){
        $('#preloader2').fadeOut('fast', function() {
            $(this).remove();
        })
      }
    window.uni_modal = function($title = '' , $url='',$size="",$showSubmit=true,$submitText='Save'){
        start_load()
        $.ajax({
            url:$url,
            error:err=>{
                console.log(err)
                alert("An error occurred")
            },
            success:function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($showSubmit ? $title : '')
                    $('#uni_modal .modal-body').html(resp)
                    if($size != ''){
                        $('#uni_modal .modal-dialog').addClass($size)
                    }else{
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                    }
                    $('#uni_modal #submit').toggle($showSubmit)
                    $('#uni_modal #submit').text($submitText)
                    $('#uni_modal .modal-footer .btn-secondary').text($showSubmit ? 'Cancel' : 'Close')
                    $('#uni_modal').modal({
                      show:true,
                      backdrop:'static',
                      keyboard:false,
                      focus:true
                    })
                    end_load()
                }
            }
        })
      }
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