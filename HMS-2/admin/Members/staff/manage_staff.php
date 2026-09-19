<?php
include("../../../Includes/config.php"); 
session_start();

// Disable MySQLi exception mode to return string errors to AJAX safely
mysqli_report(MYSQLI_REPORT_OFF);

// 1. Process Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_POST['action'])) {
    $action      = $_POST['action'] ?? '';
    $id          = (int)($_POST['id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $role        = trim($_POST['role'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $permt_addr  = trim($_POST['permt_address'] ?? '');
    $age         = (int)($_POST['age'] ?? 0);
    $gender      = trim($_POST['gender'] ?? '');
    $salary      = (float)($_POST['salary'] ?? 0);
    $join_date   = !empty($_POST['join_date']) ? $_POST['join_date'] : null;
    $last_date   = !empty($_POST['last_date']) ? $_POST['last_date'] : null;
    $is_active   = isset($_POST['is_active']) ? 1 : 0;

    // Handle File Upload for ID Proof
    $id_proof_filename = $_POST['existing_id_proof'] ?? '';
    if (isset($_FILES['id_proof']) && $_FILES['id_proof']['error'] === UPLOAD_ERR_OK) {
        $fileName      = $_FILES['id_proof']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts   = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'avif'];
        
        if (in_array($fileExtension, $allowedExts)) {
            $newFileName   = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName);
            $uploadFileDir = '../../../uploads/';
            
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            if (move_uploaded_file($_FILES['id_proof']['tmp_name'], $uploadFileDir . $newFileName)) {
                $id_proof_filename = $newFileName;
            }
        }
    }

    if ($action === 'edit' && $id > 0) {
        // UPDATE Existing Staff
        $stmt = mysqli_prepare($con, "UPDATE staff SET username=?, role=?, email=?, mobile_number=?, address=?, permanent_address=?, age=?, gender=?, monthly_salary=?, Id_proof=?, joining_date=?, last_date=?, is_active=? WHERE id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssisdsssii", $name, $role, $email, $phone, $address, $permt_addr, $age, $gender, $salary, $id_proof_filename, $join_date, $last_date, $is_active, $id);
            if (mysqli_stmt_execute($stmt)) {
                echo 1;
            } else {
                echo "Database Error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Query Preparation Failed: " . mysqli_error($con);
        }
    } else if ($action === 'add') {
        // INSERT New Staff
        $stmt = mysqli_prepare($con, "INSERT INTO staff (username, role, email, mobile_number, address, permanent_address, age, gender, monthly_salary, Id_proof, joining_date, last_date, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssisdsssi", $name, $role, $email, $phone, $address, $permt_addr, $age, $gender, $salary, $id_proof_filename, $join_date, $last_date, $is_active);
            if (mysqli_stmt_execute($stmt)) {
                echo 1;
            } else {
                echo "Database Error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Query Preparation Failed: " . mysqli_error($con);
        }
    }
    exit;
}

// 2. Fetch Staff Details if Editing
$editStaff = null;
$editId = (int)($_GET['id'] ?? $_GET['edit_id'] ?? 0);

if ($editId > 0 && !isset($_GET['action'])) {
    $stmt = mysqli_prepare($con, "SELECT * FROM staff WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $editId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $editStaff = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
    }
}
?>

<style>
    #uni_modal .modal-dialog {
        max-width: 800px !important;
        width: 70vw !important;
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

<div class="container-fluid">
    <form id="manage-staff-form" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?= $editStaff ? 'edit' : 'add' ?>">
        <?php if ($editStaff): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($editStaff['id']) ?>">
            <input type="hidden" name="existing_id_proof" value="<?= htmlspecialchars($editStaff['Id_proof'] ?? '') ?>">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($editStaff['username'] ?? '') ?>" required>
            </div>

            <div class="col-md-6 form-group">
                <label for="role">Role</label>
                <?php $currentRole = $editStaff['role'] ?? ''; ?>
                <select id="role" name="role" class="form-control">
                    <option value="Watchman Contractor" <?= ($currentRole === 'Watchman Contractor') ? 'selected' : '' ?>>Watchman Contractor</option>
                    <option value="Cleaning contractor" <?= ($currentRole === 'Cleaning contractor') ? 'selected' : '' ?>>Cleaning Contractor</option>
                    <option value="Garbage Contractor" <?= ($currentRole === 'Garbage Contractor') ? 'selected' : '' ?>>Garbage Contractor</option>
                    <option value="Plumber Contractor" <?= ($currentRole === 'Plumber Contractor') ? 'selected' : '' ?>>Plumber Contractor</option>
                    <option value="Gardener Contractor" <?= ($currentRole === 'Gardener Contractor') ? 'selected' : '' ?>>Gardener Contractor</option>
                    <option value="Civil Contractor" <?= ($currentRole === 'Civil Contractor') ? 'selected' : '' ?>>Civil Contractor</option>
                    <option value="Lift Contractor" <?= ($currentRole === 'Lift Contractor') ? 'selected' : '' ?>>Lift Contractor</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($editStaff['email'] ?? '') ?>" required>
            </div>

            <div class="col-md-6 form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($editStaff['mobile_number'] ?? '') ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($editStaff['address'] ?? '') ?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="permt_address">Permanent Address</label>
                <input type="text" id="permt_address" name="permt_address" class="form-control" value="<?= htmlspecialchars($editStaff['permanent_address'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="id_proof">Upload ID Proof (Image/PDF)</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="id_proof" name="id_proof" accept="image/*,.pdf,.avif">
                <label class="custom-file-label" for="id_proof">
                    <?= !empty($editStaff['Id_proof'] ?? '') ? htmlspecialchars($editStaff['Id_proof']) : 'Choose image file...' ?>
                </label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" class="form-control" value="<?= htmlspecialchars($editStaff['age'] ?? '0') ?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="gender">Gender</label>
                <?php $genderVal = $editStaff['gender'] ?? ''; ?>
                <select id="gender" name="gender" class="form-control">
                    <option value="" <?= ($genderVal === '') ? 'selected' : '' ?>>Select Gender</option>
                    <option value="Male" <?= ($genderVal === 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= ($genderVal === 'Female') ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= ($genderVal === 'Other') ? 'selected' : '' ?>>Other</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="salary">Salary</label>
                <input type="number" step="0.01" id="salary" name="salary" class="form-control" value="<?= htmlspecialchars($editStaff['monthly_salary'] ?? '') ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label for="join_date">Join Date</label>
                <input type="date" id="join_date" name="join_date" class="form-control" value="<?= htmlspecialchars($editStaff['joining_date'] ?? '') ?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="last_date">Last Date</label>
                <input type="date" id="last_date" name="last_date" class="form-control" value="<?= htmlspecialchars($editStaff['last_date'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group mt-2">
            <label class="d-block font-weight-bold">Is Active</label>
            <div class="custom-control custom-switch">
                <?php $isActive = isset($editStaff['is_active']) ? (int)$editStaff['is_active'] : 1; ?>
                <input type="checkbox" class="custom-control-input" id="is_active_input" name="is_active" value="1" <?= $isActive === 1 ? 'checked' : '' ?>>
                <label class="custom-control-label" for="is_active_input" id="active_status_label">
                    <?= $isActive === 1 ? 'Yes' : 'No' ?>
                </label>
            </div>
        </div>
    </form>
</div>

<script>
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    $('#is_active_input').on('change', function() {
        if($(this).is(':checked')) {
            $('#active_status_label').text('Yes');
        } else {
            $('#active_status_label').text('No');
        }
    });

    $('#manage-staff-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'manage_staff.php',
            method: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(resp) {
                if (resp.trim() == "1") {
                    location.reload();
                } else {
                    alert("Save failed:\n" + resp);
                }
            },
            error: function(xhr, status, error) {
                // Fallback: retry relative path if subfolder routing varies
                $.ajax({
                    url: 'manage_staff.php',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        if (resp.trim() == "1") {
                            location.reload();
                        } else {
                            alert("Save failed:\n" + resp);
                        }
                    },
                    error: function(err) {
                        alert("HTTP Error " + xhr.status + ": " + error);
                    }
                });
            }
        });
    });

    $('#uni_modal #submit').off('click').on('click', function() {
        $('#manage-staff-form').submit();
    });
</script>