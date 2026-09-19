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

$isActive = (int)($member['is_active'] ?? 0) === 1;
$username = $member['username'] ?? '';
$addedOn = $member['added_on'] ?? $member['created_at'] ?? '';
$updatedOn = $member['updated_on'] ?? '';
$currentAccommodation = isset($member['current_accommodation']) ? $member['current_accommodation'] : 'Owner';
$wing = '';
$flatNumber = '';
if (!empty($member['flat_id'])) {
    $flatParts = explode('-', $member['flat_id']);
    $wing = strtoupper($flatParts[0] ?? '');
    $flatNumber = implode('-', array_slice($flatParts, 1));
}
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
    .view-only-form .form-control[readonly]:focus,
    .view-only-form .form-control:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #ced4da !important;
    }
    .apartment-details-header {
        margin: 0 0 18px;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }
    .apartment-close-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 22px;
    }
    .apartment-close-btn {
        min-width: 110px;
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 20px;
    }
    .tenant-box {
        border: 1px solid #d5dbe1;
        padding: 20px;
        border-radius: 8px;
        background-color: #f8f9fa;
        margin: 15px 0 20px;
        display: <?php echo ($currentAccommodation === 'Tenant') ? 'block' : 'none'; ?>;
    }
    .apartment-switch {
        margin-top: 8px;
    }
    .apartment-switch .custom-control-label::before {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .apartment-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745;
        border-color: #28a745;
    }
    .apartment-switch {
        padding-left: 2.25rem;
    }
    .apartment-switch .custom-control-input {
        position: absolute;
        z-index: -1;
        opacity: 0;
    }
    .apartment-switch .custom-control-label {
        position: relative;
        margin-bottom: 0;
        vertical-align: top;
    }
    .apartment-switch .custom-control-label::before,
    .apartment-switch .custom-control-label::after {
        position: absolute;
        display: block;
        content: "";
    }
    .apartment-switch .custom-control-label::before {
        top: 0.25rem;
        left: -2.25rem;
        width: 1.75rem;
        height: 1rem;
        border-radius: 1rem;
        transition: background-color 0.15s ease-in-out;
    }
    .apartment-switch .custom-control-label::after {
        top: 0.375rem;
        left: -2rem;
        width: 0.75rem;
        height: 0.75rem;
        background-color: #fff;
        border-radius: 50%;
        transition: transform 0.15s ease-in-out;
    }
    .apartment-switch .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(0.75rem);
    }
</style>

<div class="container-fluid view-only-form">
    <?php if ($member): ?>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="wing">Wing</label>
                <input type="text" id="wing" class="form-control" value="<?= htmlspecialchars($wing ?: 'A') ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="flat_number">Flat No.</label>
                <input type="text" id="flat_number" class="form-control" value="<?= htmlspecialchars($flatNumber ?: 'N/A') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="name">Owner Name</label>
                <input type="text" id="name" class="form-control" value="<?= htmlspecialchars($member['name'] ?? '') ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="phone">Contact No.</label>
                <input type="text" id="phone" class="form-control" value="<?= htmlspecialchars($member['phone'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($member['email'] ?? '') ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="current_accommodation">Current Accommodation</label>
            <input type="text" id="current_accommodation" class="form-control" value="<?= htmlspecialchars($currentAccommodation) ?>" readonly>
        </div>

        <div id="tenant_section" class="tenant-box">
            <h5 class="fw-bold mb-3 text-primary">Tenant Information</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="tenant_name">Tenant Name</label>
                    <input type="text" id="tenant_name" class="form-control" value="<?= htmlspecialchars($member['tenant_name'] ?? 'N/A') ?>" readonly>
                </div>
                <div class="col-md-6 form-group">
                    <label for="tenant_contact">Tenant Contact No.</label>
                    <input type="text" id="tenant_contact" class="form-control" value="<?= htmlspecialchars($member['tenant_contact'] ?? 'N/A') ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="tenant_start_date">Tenant Start Date</label>
                    <input type="text" id="tenant_start_date" class="form-control" value="<?= !empty($member['tenant_start_date'] ?? '') ? date('M d, Y', strtotime($member['tenant_start_date'])) : 'N/A' ?>" readonly>
                </div>
                <div class="col-md-6 form-group">
                    <label for="tenant_end_date">Tenant End Date</label>
                    <input type="text" id="tenant_end_date" class="form-control" value="<?= !empty($member['tenant_end_date'] ?? '') ? date('M d, Y', strtotime($member['tenant_end_date'])) : 'N/A' ?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="owner_start_date">Owner Accommodation Start Date</label>
                <input type="text" id="owner_start_date" class="form-control" value="<?= !empty($member['owner_start_date'] ?? '') ? date('M d, Y', strtotime($member['owner_start_date'])) : 'N/A' ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="parking_allotted">Parking Allotted</label>
                <input type="text" id="parking_allotted" class="form-control" value="<?= htmlspecialchars($member['parking_allotted'] ?? 'NO') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="added_on">Added On</label>
                <input type="text" id="added_on" class="form-control" value="<?= !empty($addedOn) ? date('M d, Y H:i', strtotime($addedOn)) : 'N/A' ?>" readonly>
            </div>
            <div class="col-md-6 form-group">
                <label for="updated_on">Updated On</label>
                <input type="text" id="updated_on" class="form-control" value="<?= !empty($updatedOn) ? date('M d, Y H:i', strtotime($updatedOn)) : 'N/A' ?>" readonly>
            </div>
        </div>

        <div class="form-group">
            <label class="d-block" for="view_is_active">Is Active</label>
            <div class="custom-control custom-switch apartment-switch">
                <input type="checkbox" class="custom-control-input" id="view_is_active" <?= $isActive ? 'checked' : '' ?> disabled>
                <label class="custom-control-label" for="view_is_active"><?= $isActive ? 'Yes' : 'No' ?></label>
            </div>
        </div>

        <div id="apartmentViewActions" class="apartment-close-row">
            <button type="button" id="apartmentCloseBtn" class="btn btn-secondary apartment-close-btn" data-dismiss="modal">Close</button>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">Apartment details not found.</div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const closeBtn = document.getElementById('apartmentCloseBtn');
        if (!closeBtn) return;

        closeBtn.addEventListener('click', function (event) {
            event.preventDefault();

            if (window.parent && window.parent !== window && window.parent.jQuery && window.parent.jQuery('#uni_modal').length) {
                window.parent.jQuery('#uni_modal').modal('hide');
                return;
            }

            if (window.jQuery && jQuery('#uni_modal').length && jQuery('#uni_modal').hasClass('show')) {
                jQuery('#uni_modal').modal('hide');
                return;
            }

            const ref = document.referrer || '';
            if (ref && (ref.indexOf('view_wing.php') !== -1 || ref.indexOf('wings.php') !== -1 || ref.indexOf('index.php') !== -1)) {
                window.history.back();
                return;
            }

            window.location.href = '../Dashboard/view_wing.php';
        });
    });
</script>
