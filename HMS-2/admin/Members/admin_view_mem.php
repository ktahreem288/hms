<?php 
include("../../Includes/config.php"); 
session_start();

if(isset($_GET['id'])){
    $member = $con->query("SELECT * FROM member WHERE id = ".$_GET['id']);
    if($member && $member->num_rows > 0){
        $meta = $member->fetch_array();
    }
}

$role_map = [
    1 => 'Chairman',
    2 => 'Secretary',
    3 => 'Treasurer',
    4 => 'Committee Member',
    5 => 'Manager'
];
?>

<div class="container-fluid py-2">
    <div class="row g-3">
        <!-- Name -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Name</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($meta['name'] ?? 'N/A'); ?>" readonly>
        </div>

        <!-- Phone -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Phone</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($meta['phone'] ?? 'N/A'); ?>" readonly>
        </div>

        <!-- Flat No -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Flat No / Address</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($meta['flat_id'] ?? 'N/A'); ?>" readonly>
        </div>

        <!-- Role -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Role</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($role_map[$meta['member_role'] ?? 0] ?? 'Member'); ?>" readonly>
        </div>

        <!-- Is Active -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Is Active</label>
            <input type="text" class="form-control bg-light" value="<?php echo (isset($meta['is_active']) && $meta['is_active'] == 1) ? 'Active' : 'Inactive'; ?>" readonly>
        </div>

        <!-- Added On -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Added On</label>
            <input type="text" class="form-control bg-light" value="<?php echo !empty($meta['created_at']) ? date("M d, Y H:i", strtotime($meta['created_at'])) : 'N/A'; ?>" readonly>
        </div>

        <!-- Added By -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Added By</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($meta['created_by'] ?? 'System / Admin'); ?>" readonly>
        </div>

        <!-- Updated On -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Updated On</label>
            <input type="text" class="form-control bg-light" value="<?php echo !empty($meta['updated_at']) ? date("M d, Y H:i", strtotime($meta['updated_at'])) : 'N/A'; ?>" readonly>
        </div>

        <!-- Updated By -->
        <div class="col-md-6">
            <label class="form-label text-muted small mb-1">Updated By</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($meta['updated_by'] ?? 'N/A'); ?>" readonly>
        </div>
    </div>
</div>

<div class="modal-footer px-0 pb-0 mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="if(typeof $('#uni_modal').modal === 'function') $('#uni_modal').modal('hide');">Cancel</button>
</div>