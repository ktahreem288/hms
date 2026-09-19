<?php 
include("../../Includes/config.php"); 
session_start();

$is_view = isset($_GET['action']) && $_GET['action'] == 'view';

if(isset($_GET['id'])){
    $member = $con->query("SELECT * FROM member WHERE id = ".$_GET['id']);
    if($member && $member->num_rows > 0){
        $meta = $member->fetch_array();
    }
}
?>

<div class="container-fluid">
    <div id="msg"></div>
    
    <form action="" id="manage-user">   
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
        
        <div class="form-group mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required autocomplete="off" <?php echo $is_view ? 'readonly' : ''; ?>>
        </div>

        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required autocomplete="off" <?php echo $is_view ? 'readonly' : ''; ?>>
        </div>

        <?php if(!$is_view): ?>
        <div class="form-group mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" <?php echo isset($meta['id']) ? '' : 'required' ?>>
            <?php if(isset($meta['id'])): ?>
                <small class="text-muted"><i>Leave this blank if you don't want to change the password.</i></small>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="form-group mb-3">
            <label for="flat_id" class="form-label">Flat No / Address</label>
            <input type="text" name="flat_id" id="flat_id" class="form-control" value="<?php echo isset($meta['flat_id']) ? $meta['flat_id']: '' ?>" autocomplete="off" <?php echo $is_view ? 'readonly' : ''; ?>>
        </div>

        <div class="form-group mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo isset($meta['phone']) ? $meta['phone']: '' ?>" autocomplete="off" <?php echo $is_view ? 'readonly' : ''; ?>>
        </div>

        <div class="form-group mb-3">
            <label for="member_role" class="form-label">Role</label>
            <select name="member_role" id="member_role" class="form-select select2" <?php echo $is_view ? 'disabled' : ''; ?>>
                <option value="1" <?php echo isset($meta['member_role']) && $meta['member_role'] == 1 ? 'selected': '' ?>>Chairman</option>
                <option value="2" <?php echo isset($meta['member_role']) && $meta['member_role'] == 2 ? 'selected': '' ?>>Secretary</option>
                <option value="3" <?php echo isset($meta['member_role']) && $meta['member_role'] == 3 ? 'selected': '' ?>>Treasurer</option>
                <option value="4" <?php echo isset($meta['member_role']) && $meta['member_role'] == 4 ? 'selected': '' ?>>Committee Member</option>
                <option value="5" <?php echo isset($meta['member_role']) && $meta['member_role'] == 5 ? 'selected': '' ?>>Manager</option>
            </select>
        </div>

        <!-- Is Active Switch Toggle -->
        <div class="form-group mb-3">
            <label class="form-label d-block fw-bold mb-2">Is Active</label>
            <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                <input class="form-check-input ms-0" type="checkbox" role="switch" id="is_active_toggle" name="is_active" value="1" <?php echo (!isset($meta['is_active']) || $meta['is_active'] == 1) ? 'checked' : ''; ?> <?php echo $is_view ? 'disabled' : ''; ?>>
                <label class="form-check-label fw-semibold" for="is_active_toggle" id="active_label">
                    <?php echo (!isset($meta['is_active']) || $meta['is_active'] == 1) ? 'Yes' : 'No'; ?>
                </label>
            </div>
        </div>
    </form>
</div>

<script>
    if ($.fn.select2) {
        $('.select2').select2({
            placeholder: "Please select here",
            width: "100%"
        });
    }

    <?php if(!$is_view): ?>
    // Toggle switch Yes/No text display
    $('#is_active_toggle').change(function(){
        if($(this).is(':checked')) {
            $('#active_label').text('Yes');
        } else {
            $('#active_label').text('No');
        }
    });

    // Handle form submit
    $('#manage-user').submit(function(e){
        e.preventDefault();
        if (typeof start_load === 'function') start_load();
        $('#msg').html('');

        var formData = $(this).serializeArray();
        
        // Ensure is_active=0 is sent if checkbox is unchecked
        var hasActive = formData.some(function(item) { return item.name === 'is_active'; });
        if (!hasActive) {
            formData.push({ name: 'is_active', value: '0' });
        }

        $.ajax({
            url: 'ajax.php?action=save_user',
            method: 'POST',
            data: $.param(formData),
            success: function(resp){
                if (resp == 1) {
                    if (typeof alert_toast === 'function') alert_toast("Data successfully saved", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else {
                    $('#msg').html('<div class="alert alert-danger">Error saving data or email already exists.</div>');
                    if (typeof end_load === 'function') end_load();
                }
            }
        });
    });
    <?php endif; ?>
</script>