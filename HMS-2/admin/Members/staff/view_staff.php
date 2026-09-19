<?php
include("../../../Includes/config.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$staff = null;

if ($id > 0) {
    $stmt = mysqli_prepare($con, "SELECT * FROM staff WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $staff = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}
?>

<style>
    #uni_modal .modal-dialog {
        max-width: 800px !important;
        width: 70vw !important;
    }
    .view-only-form .form-control[readonly] {
        background-color: #f8f9fa !important;
        color: #495057 !important;
        opacity: 1;
        cursor: not-allowed;
    }
    .custom-switch .custom-control-label::before {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>

<div class="container-fluid view-only-form">
    <?php if ($staff): ?>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" class="form-control" value="<?= htmlspecialchars($staff['username'] ?? $staff['Name'] ?? $staff['name'] ?? '') ?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="role">Role</label>
                <input type="text" id="role" class="form-control" value="<?= htmlspecialchars($staff['Role'] ?? $staff['role'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($staff['email'] ?? $staff['Email'] ?? '') ?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" class="form-control" value="<?= htmlspecialchars($staff['mobile_number'] ?? $staff['phone'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="address">Address</label>
                <input type="text" id="address" class="form-control" value="<?= htmlspecialchars($staff['Address'] ?? $staff['address'] ?? '') ?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="permt_address">Permanent Address</label>
                <input type="text" id="permt_address" class="form-control" value="<?= htmlspecialchars($staff['permanent_address'] ?? $staff['Permanent_Address'] ?? '') ?>" readonly>
            </div>
        </div>

        <!-- ID Proof Display (No Browse Button) -->
        <div class="form-group">
            <label for="id_proof">Upload ID Proof (Image/PDF)</label>
            <?php $id_proof = $staff['Id_proof'] ?? $staff['id_proof'] ?? $staff['ID_Proof'] ?? ''; ?>
            <input type="text" class="form-control" value="<?= !empty($id_proof) ? htmlspecialchars($id_proof) : 'No file uploaded' ?>" readonly>
            <?php if (!empty($id_proof)): ?>
                <div class="mt-2">
                    <a href="../../../uploads/<?= htmlspecialchars($id_proof) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-eye"></i> View Attachment
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                <label for="age">Age</label>
                <input type="number" id="age" class="form-control" value="<?= htmlspecialchars($staff['age'] ?? $staff['Age'] ?? '0') ?>" readonly>
            </div>

            <div class="col-md-4 form-group">
                <label for="gender">Gender</label>
                <input type="text" id="gender" class="form-control" value="<?= htmlspecialchars($staff['gender'] ?? $staff['Gender'] ?? '') ?>" readonly>
            </div>

            <div class="col-md-4 form-group">
                <label for="salary">Salary</label>
                <input type="text" id="salary" class="form-control" value="<?= htmlspecialchars($staff['monthly_salary'] ?? $staff['salary'] ?? $staff['Salary'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="join_date">Join Date</label>
                <input type="date" id="join_date" class="form-control" value="<?= htmlspecialchars($staff['joining_date'] ?? $staff['join_date'] ?? $staff['Joining_Date'] ?? '') ?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="last_date">Last Date</label>
                <input type="date" id="last_date" class="form-control" value="<?= htmlspecialchars($staff['last_date'] ?? $staff['Last_Date'] ?? '') ?>" readonly>
            </div>
        </div>

        <div class="form-group mt-2">
            <label class="d-block font-weight-bold">Is Active</label>
            <div class="custom-control custom-switch">
                <?php $isActive = isset($staff['is_active']) ? (int)$staff['is_active'] : 0; ?>
                <input type="checkbox" class="custom-control-input" id="is_active_view" <?= $isActive === 1 ? 'checked' : '' ?> disabled>
                <label class="custom-control-label" for="is_active_view">
                    <?= $isActive === 1 ? 'Yes' : 'No' ?>
                </label>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">
            Staff details not found.
        </div>
    <?php endif; ?>
</div>