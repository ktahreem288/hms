<?php 

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h4 class="card-title mb-0"><b>Manage Members</b></h4>
					<button class="btn btn-primary btn-sm" id="new_user"><i class="fa fa-plus"></i> Add Member</button>
				</div>
			<div class="card-body">
				<table class="table table-striped table-bordered col-md-12 member-management-table">
			<thead>
				<tr>
					<th class="text-center">ID</th>
					<th>Role</th>
					<th>Name</th>
					<th>Username</th>
					<th class="text-center">View / Edit</th>
					<th class="text-center">Is Active</th>
					<th class="text-center">Added On Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
 					// include ("../../Includes/config.php"); 
 					$member = $con->query("SELECT * FROM member ORDER BY name ASC");
					$i = 1;
 					while($row= $member->fetch_assoc()):
						$roleLabels = [1 => 'Chairman', 2 => 'Secretary', 3 => 'Treasurer', 4 => 'Committee Member', 5 => 'Manager'];
						$role = $roleLabels[(int)($row['member_role'] ?? 0)] ?? 'Not assigned';
						$isActive = (int)($row['is_active'] ?? 0) === 1;
				 ?>
				 <tr>
				 	<td class="text-center">
				 		<?php echo (int)$row['id'] ?>
				 	</td>
				 	<td>
				 		<?php echo htmlspecialchars($role) ?>
				 	</td>
				 	
				 	<td>
				 		<strong><?php echo htmlspecialchars($row['name'] ?? '') ?></strong>
				 	</td>
				 	<td><?php echo htmlspecialchars($row['username'] ?? '') ?></td>
				 	<td>
				 		<div class="btn-group btn-group-sm" role="group">
				 			<button type="button" class="btn btn-outline-info view_user" data-id="<?php echo (int)$row['id'] ?>">View</button>
				 			<button type="button" class="btn btn-outline-primary edit_user" data-id="<?php echo (int)$row['id'] ?>">Edit</button>
				 		</div>
				 	</td>
				 	<td class="text-center">
				 		<span class="badge <?php echo $isActive ? 'badge-success' : 'badge-secondary' ?>">
				 			<?php echo $isActive ? 'Active' : 'Inactive' ?>
				 		</span>
				 	</td>
				 	<td class="text-center">
				 		<?php echo !empty($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : 'N/A' ?>
				 	</td>
				 </tr>
				<?php endwhile; ?>
			</tbody>
		</table>
			</div>
			</div>
		</div>
	</div>

</div>
<?php if (isset($_GET['from_dashboard']) && $_GET['from_dashboard'] === '1'): ?>
	<div class="member-page-back">
		<a href="../Dashboard/admin.php" class="btn btn-secondary btn-sm">
			<i class="fa fa-arrow-left"></i> Back to Dashboard
		</a>
	</div>
<?php endif; ?>
<script>
	$('table').dataTable({
		lengthChange: false,
		info: false
	});
$('#new_user').click(function(){
	uni_modal('Add Member','manage_user.php','modal-lg',true,'Save to Add')
})
$('.edit_user').click(function(){
	uni_modal('Edit Member Details','manage_user.php?id='+$(this).attr('data-id'),'modal-lg')
})
$('.view_user').click(function(){
	uni_modal('View Members Details', 'view_member.php?id=' + $(this).attr('data-id') + '&view_type=member', 'modal-lg', false)
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