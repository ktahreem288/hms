<?php 
  // Ensure config file is included to get database connection
  if (!isset($con) && !isset($conn)) {
      require_once("../../Includes/config.php"); 
  }

  // Set active database connection variable
  $db = isset($con) ? $con : (isset($conn) ? $conn : null);

  if (!$db) {
      echo '<div class="alert alert-danger m-3">Database Connection Error: Could not establish connection.</div>';
      exit;
  }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!-- <button class="btn btn-primary float-right btn-sm" id="new_user"><i class="fa fa-plus"></i> New member</button> -->
        </div>
    </div>
    <br>
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <table class="table table-striped table-bordered col-md-12">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Username</th>
                            <!-- <th class="text-center">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $member = $db->query("SELECT * FROM member ORDER BY username ASC");
                            $i = 1;
                            if ($member && $member->num_rows > 0):
                                while($row = $member->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php echo $i++ ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(ucwords($row['email'] ?? '')) ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['username'] ?? '') ?>
                            </td>
                        </tr>
                        <?php 
                                endwhile; 
                            else:
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">No members found in database.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    if ($.fn.dataTable) {
        $('table').dataTable();
    }

    $('#new_user').click(function(){
        uni_modal('New Member','manage_user.php')
    })
    
    $('.edit_user').click(function(){
        uni_modal('Edit Member','manage_user.php?id='+$(this).attr('data-id'))
    })
    
    $('.delete_user').click(function(){
        _conf("Are you sure to delete this member?","delete_user",[$(this).attr('data-id')])
    })

    function delete_user($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_user',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully deleted",'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    }
</script>