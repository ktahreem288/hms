<?php
require_once("../../Includes/config.php");

$id = (int)($_GET['id'] ?? 0);
$member = null;

if ($id > 0) {
    $stmt = mysqli_prepare($con, "SELECT * FROM member WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $member = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

$viewType = strtolower($_GET['view_type'] ?? 'apartment');
$pageTitle = $viewType === 'apartment' ? 'View Apartment Details' : 'View Members Details';

$roleLabels = [1 => 'Chairman', 2 => 'Secretary', 3 => 'Treasurer', 4 => 'Committee Member', 5 => 'Manager'];
$role = $roleLabels[(int)($member['member_role'] ?? 0)] ?? 'Not assigned';
$isActive = (int)($member['is_active'] ?? 0) === 1;
$username = $member['username'] ?? '';
$addedOn = $member['added_on'] ?? $member['created_at'] ?? '';
$updatedOn = $member['updated_on'] ?? '';
?>

<style>
    #uni_modal .modal-dialog {
        max-width: 850px !important;
        width: 72vw !important;
    }
    .view-only-form .form-control[readonly] {
        background-color: #f8f9fa !important;
        color: #495057 !important;
        opacity: 1;
        cursor: not-allowed;
    }
</style>

<div class="container-fluid view-only-form">
    <?php if ($member): ?>
        <h3 class="member-details-header-text"><?= htmlspecialchars($pageTitle) ?></h3>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="name">Owner Name</label>
                <input type="text" id="name" class="form-control" value="<?= htmlspecialchars($member['name'] ?? '') ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="phone">Mobile / Phone</label>
                <input type="text" id="phone" class="form-control" value="<?= htmlspecialchars($member['phone'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="role">Role</label>
                <input type="text" id="role" class="form-control" value="<?= htmlspecialchars($role) ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($member['email'] ?? '') ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="flat_id">Apartment ID</label>
                <input type="text" id="flat_id" class="form-control" value="<?= htmlspecialchars($member['flat_id'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="added_on">Added On</label>
                <input type="text" id="added_on" class="form-control" value="<?= !empty($addedOn) ? date('M d, Y H:i', strtotime($addedOn)) : 'N/A' ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="added_by">Added By</label>
                <input type="text" id="added_by" class="form-control" value="<?= htmlspecialchars($member['added_by'] ?? 'N/A') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="updated_on">Updated On</label>
                <input type="text" id="updated_on" class="form-control" value="<?= !empty($updatedOn) ? date('M d, Y H:i', strtotime($updatedOn)) : 'N/A' ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="updated_by">Updated By</label>
                <input type="text" id="updated_by" class="form-control" value="<?= htmlspecialchars($member['updated_by'] ?? 'N/A') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
                <label for="status">Status</label>
                <input type="text" id="status" class="form-control" value="<?= $isActive ? 'Active' : 'Inactive' ?>" readonly>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-danger text-center">Member details not found.</div>
    <?php endif; ?>
</div>
