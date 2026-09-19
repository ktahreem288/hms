<?php 
include("../../Includes/config.php"); 
if(isset($_GET['id'])){
$member = $con->query("SELECT * FROM member where id =".$_GET['id']);
foreach($member->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
$memberRoles = [
	1 => 'Chairman',
	2 => 'Secretary',
	3 => 'Treasurer',
	4 => 'Committee Member',
	5 => 'Manager'
];
$isApartmentEdit = (
    (isset($_GET['is_apartment']) && $_GET['is_apartment'] == 1) ||
    (isset($_GET['from']) && $_GET['from'] === 'apartment') ||
    (!empty($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'view_wing.php') !== false) ||
    (!empty($meta['flat_id']) && ((int)($meta['member_role'] ?? 0) === 0))
);
$currentMemberRole = (int)($meta['member_role'] ?? 0);
$isActive = (int)($meta['is_active'] ?? 1) === 1;
$currentAccommodation = isset($meta['current_accommodation']) ? $meta['current_accommodation'] : 'Owner';
$wing = '';
$flatNumber = '';
if (!empty($meta['flat_id'])) {
    $flatParts = explode('-', $meta['flat_id']);
    $wing = strtoupper($flatParts[0] ?? '');
    $flatNumber = implode('-', array_slice($flatParts, 1));
}
?>
<style>
	#uni_modal .modal-dialog {
		max-width: 800px !important;
		width: 70vw !important;
	}
	#manage-user .custom-switch .custom-control-label::before {
		background-color: #dc3545;
		border-color: #dc3545;
	}
	#manage-user .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
		background-color: #28a745;
		border-color: #28a745;
	}
	#manage-user .custom-switch {
		padding-left: 2.25rem;
	}
	#manage-user .custom-switch .custom-control-input {
		position: absolute;
		z-index: -1;
		opacity: 0;
	}
	#manage-user .custom-switch .custom-control-label {
		position: relative;
		margin-bottom: 0;
		vertical-align: top;
	}
	#manage-user .custom-switch .custom-control-label::before,
	#manage-user .custom-switch .custom-control-label::after {
		position: absolute;
		display: block;
		content: "";
	}
	#manage-user .custom-switch .custom-control-label::before {
		top: 0.25rem;
		left: -2.25rem;
		width: 1.75rem;
		height: 1rem;
		border-radius: 1rem;
		transition: background-color 0.15s ease-in-out;
	}
	#manage-user .custom-switch .custom-control-label::after {
		top: 0.375rem;
		left: -2rem;
		width: 0.75rem;
		height: 0.75rem;
		background-color: #fff;
		border-radius: 50%;
		transition: transform 0.15s ease-in-out;
	}
	#manage-user .custom-switch .custom-control-input:checked ~ .custom-control-label::after {
		transform: translateX(0.75rem);
	}
	#manage-user .tenant-box {
		border: 1px solid #d5dbe1;
		padding: 20px;
		border-radius: 8px;
		background: #f8f9fa;
		margin-top: 15px;
		margin-bottom: 20px;
		display: none;
	}
</style>
<div class="container-fluid">
	<div id="msg"></div>
	
	<form action="" id="manage-user">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<?php if ($isApartmentEdit): ?>
		<input type="hidden" name="flat_id" id="flat_id" value="<?php echo htmlspecialchars($meta['flat_id'] ?? '') ?>">
		<div class="row">
			<div class="col-md-6 form-group">
				<label for="wing">Wing</label>
				<select name="wing" id="wing" class="form-control" required>
					<?php foreach(range('A','J') as $w): ?>
						<option value="<?php echo $w; ?>" <?php echo ($wing === $w) ? 'selected' : ''; ?>><?php echo $w; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-6 form-group">
				<label for="flat_number">Flat No.</label>
				<input type="text" name="flat_number" id="flat_number" class="form-control" value="<?php echo htmlspecialchars($flatNumber); ?>" required>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 form-group">
				<label for="name">Owner Name</label>
				<input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($meta['name'] ?? '') ?>" required autocomplete="off">
			</div>
			<div class="col-md-6 form-group">
				<label for="phone">Contact No.</label>
				<input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($meta['phone'] ?? '') ?>" required>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 form-group">
				<label for="email">Email</label>
				<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required>
			</div>
			<div class="col-md-6 form-group">
				<label for="username">Username</label>
				<input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($meta['username'] ?? '') ?>" required autocomplete="off">
			</div>
		</div>
		<div class="form-group">
			<label for="current_accommodation">Current Accommodation</label>
			<select name="current_accommodation" id="current_accommodation" class="form-control" onchange="toggleTenantSection()">
				<option value="Owner" <?php echo ($currentAccommodation === 'Owner') ? 'selected' : ''; ?>>Owner</option>
				<option value="Tenant" <?php echo ($currentAccommodation === 'Tenant') ? 'selected' : ''; ?>>Tenant</option>
			</select>
		</div>

		<div id="tenant_section" class="tenant-box">
			<h5 class="fw-bold mb-3 text-primary">Tenant Information</h5>
			<div class="row">
				<div class="col-md-6 form-group">
					<label for="tenant_name">Tenant Name</label>
					<input type="text" name="tenant_name" id="tenant_name" class="form-control" placeholder="Enter tenant name">
				</div>
				<div class="col-md-6 form-group">
					<label for="tenant_contact">Tenant Contact No.</label>
					<input type="text" name="tenant_contact" id="tenant_contact" class="form-control" placeholder="Enter tenant contact">
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 form-group">
					<label for="tenant_start_date">Tenant Start Date</label>
					<input type="date" name="tenant_start_date" id="tenant_start_date" class="form-control">
				</div>
				<div class="col-md-6 form-group">
					<label for="tenant_end_date">Tenant End Date</label>
					<input type="date" name="tenant_end_date" id="tenant_end_date" class="form-control">
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 form-group">
					<label for="rent_agreement">Rent Agreement</label>
					<input type="file" name="rent_agreement" id="rent_agreement" class="form-control">
				</div>
				<div class="col-md-6 form-group">
					<label for="police_verification">Police Verification</label>
					<input type="file" name="police_verification" id="police_verification" class="form-control">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 form-group">
				<label for="owner_start_date">Owner Accommodation Start Date</label>
				<input type="date" name="owner_start_date" id="owner_start_date" class="form-control" value="<?php echo htmlspecialchars($meta['owner_start_date'] ?? '') ?>">
			</div>
			<div class="col-md-6 form-group">
				<label for="parking_allotted">Parking Allotted</label>
				<select name="parking_allotted" id="parking_allotted" class="form-control">
					<option value="YES" <?php echo (($meta['parking_allotted'] ?? '') === 'YES') ? 'selected' : ''; ?>>YES</option>
					<option value="NO" <?php echo (($meta['parking_allotted'] ?? 'NO') === 'NO') ? 'selected' : ''; ?>>NO</option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label for="noc_issued">NOC Issued</label>
			<input type="file" name="noc_issued" id="noc_issued" class="form-control">
		</div>
		<div class="form-group">
			<label class="d-block" for="is_active_input">Is Active</label>
			<div class="custom-control custom-switch mt-2">
				<input type="hidden" name="is_active" value="0">
				<input type="checkbox" class="custom-control-input" name="is_active" id="is_active_input" value="1" <?php echo $isActive ? 'checked' : '' ?>>
				<label class="custom-control-label" for="is_active_input" id="active_status_label"><?php echo $isActive ? 'Yes' : 'No' ?></label>
			</div>
		</div>
		<input type="hidden" name="member_role" value="0">
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
			<?php if(isset($meta['id'])): ?>
			<small><i>Leave this blank if you dont want to change the password.</i></small>
			<?php endif; ?>
		</div>
		<?php else: ?>
		<div class="form-group">
			<label for="name">Email</label>
			<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($meta['name'] ?? '') ?>" required autocomplete="off">
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($meta['username'] ?? '') ?>" required autocomplete="off">
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="phone">Mobile / Phone</label>
				<input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($meta['phone'] ?? '') ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="flat_id">Flat No</label>
				<input type="text" name="flat_id" id="flat_id" class="form-control" value="<?php echo htmlspecialchars($meta['flat_id'] ?? '') ?>">
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="member_role">Role</label>
				<select name="member_role" id="member_role" class="form-control" required>
					<option value="">Select Role</option>
					<?php foreach($memberRoles as $roleId => $roleName): ?>
						<option value="<?php echo $roleId ?>" <?php echo $currentMemberRole === $roleId ? 'selected' : '' ?>><?php echo $roleName ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label class="d-block" for="is_active_input">Is Active</label>
				<div class="custom-control custom-switch mt-2">
					<input type="hidden" name="is_active" value="0">
					<input type="checkbox" class="custom-control-input" name="is_active" id="is_active_input" value="1" <?php echo $isActive ? 'checked' : '' ?>>
					<label class="custom-control-label" for="is_active_input" id="active_status_label"><?php echo $isActive ? 'Yes' : 'No' ?></label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
			<?php if(isset($meta['id'])): ?>
			<small><i>Leave this blank if you dont want to change the password.</i></small>
		<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if ($isApartmentEdit): ?>
		<div id="apartmentFormActions" class="d-flex justify-content-end mt-4">
			<a href="../Dashboard/view_wing.php" class="btn btn-secondary mr-2">Cancel</a>
			<button type="submit" class="btn btn-primary">Save</button>
		</div>
		<?php endif; ?>
	</form>
</div>
<script>
	function toggleTenantSection() {
		var accommodation = document.getElementById('current_accommodation');
		var tenantBox = document.getElementById('tenant_section');
		if (accommodation && tenantBox) {
			tenantBox.style.display = (accommodation.value === 'Tenant') ? 'block' : 'none';
		}
	}
	$(function () {
		var isApartmentEdit = <?php echo $isApartmentEdit ? 'true' : 'false'; ?>;
		if (isApartmentEdit) {
			toggleTenantSection();
			$('#wing, #flat_number').on('change input', function(){
				$('#flat_id').val($('#wing').val() + '-' + $('#flat_number').val().trim());
			});
		}
		$('.select2').select2({
			placeholder:"Please select here",
			width:"100%"
		})
		$('#is_active_input').on('change', function(){
			$('#active_status_label').text($(this).is(':checked') ? 'Yes' : 'No')
		})	
		$(document).off('submit.manageUser', '#manage-user').on('submit.manageUser', '#manage-user', function(e){
			e.preventDefault();
			start_load()
			$.ajax({
				url:'ajax.php?action=save_user',
				method:'POST',
				data:$(this).serialize(),
				success:function(resp){
					resp = $.trim(resp)
					if(resp === '1'){
						location.reload()
					} else {
						end_load()
						alert("Save failed:\n" + (resp || 'The server returned an empty response.'))
					}
				},
				error:function(xhr){
					end_load()
					alert("Save failed:\nHTTP " + xhr.status + " " + xhr.statusText)
				}
			})
		})
	});
</script>