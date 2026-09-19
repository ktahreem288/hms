<?php 
  include_once("../../../Includes/config.php"); 
?>

<div class="container-fluid">
    <div class="row mt-3 mb-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><b>Manage Staff</b></h4>
                    <button class="btn btn-primary btn-sm float-right" id="new_staff">
                        <i class="fa fa-plus"></i> Add Staff
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="staff-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">id</th>
                                <th>Role</th>
                                <th>Name</th>
                                <th class="text-center" style="width: 120px;">View/Edit</th>
                                <th class="text-center" style="width: 120px;">is_active</th>
                                <th class="text-center" style="width: 150px;">added_on_date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT * FROM staff ORDER BY id DESC";
                                $staff = mysqli_query($con, $query);
                                $i = 1;

                                if ($staff && mysqli_num_rows($staff) > 0):
                                    while($row = mysqli_fetch_assoc($staff)):
                            ?>
                            <tr>
                                <td class="text-center align-middle"><?php echo $i++; ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($row['Role'] ?? $row['role'] ?? 'N/A'); ?></td>
                                <td class="align-middle"><b><?php echo htmlspecialchars($row['Name'] ?? $row['name'] ?? $row['username'] ?? 'N/A'); ?></b></td>
                                <td class="text-center align-middle">
                                    <div class="btn-group-vertical btn-group-sm w-100">
                                        <button class="btn btn-outline-info btn-sm view_staff" type="button" data-id="<?php echo $row['id']; ?>">View</button>
                                        <button class="btn btn-outline-primary btn-sm edit_staff" type="button" data-id="<?php echo $row['id']; ?>">Edit</button>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if(isset($row['is_active']) && $row['is_active'] == 1): ?>
                                        <span class="badge badge-success px-3 py-2">active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary px-3 py-2">inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <?php 
                                        $date = $row['Joining_Date'] ?? $row['joining_date'] ?? $row['added_on_date'] ?? '';
                                        echo !empty($date) ? date('M d, Y', strtotime($date)) : 'N/A'; 
                                    ?>
                                </td>
                            </tr>
                            <?php 
                                    endwhile;
                                endif; 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_GET['from_dashboard']) && $_GET['from_dashboard'] === '1'): ?>
    <div class="staff-page-back">
        <a href="../../Dashboard/admin.php" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
<?php endif; ?>

<script>
    $('#staff-table').dataTable({
        lengthChange: false,
        info: false
    });

    $('#new_staff').click(function(){
        uni_modal('Add Staff Details', 'manage_staff.php', 'modal-lg', true, 'Save to Add');
    });

    $('.view_staff').click(function(){
        uni_modal('View Staff Details', 'view_staff.php?id=' + $(this).attr('data-id'), 'modal-md', false);
    });

    $('.edit_staff').click(function(){
        uni_modal('Edit Staff Details', 'manage_staff.php?id=' + $(this).attr('data-id'), 'modal-lg');
    });
</script>