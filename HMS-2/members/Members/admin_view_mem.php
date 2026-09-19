<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold">Manage Committee</h2>
        <button class="btn btn-primary btn-sm" id="new_member">
            <i class='bx bx-plus'></i> Add member
        </button>
    </div>

    <!-- Committee Table Section -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 80px;">id</th>
                            <th scope="col">Role</th>
                            <th scope="col">Name</th>
                            <th scope="col">View/Edit</th>
                            <th scope="col">is_active</th>
                            <th scope="col">Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo isset($row['role']) ? htmlspecialchars($row['role']) : 'Chairman'; ?></td>
                            <td><?php echo htmlspecialchars($row['name'] ?? $row['username']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit_user" data-id="<?php echo $row['id']; ?>">View / Edit</button>
                            </td>
                            <td>
                                <span class="badge <?php echo (isset($row['is_active']) && $row['is_active'] == 0) ? 'bg-secondary' : 'bg-success'; ?>">
                                    <?php echo (isset($row['is_active']) && $row['is_active'] == 0) ? 'Inactive' : 'Active'; ?>
                                </span>
                            </td>
                            <td><?php echo isset($row['date_created']) ? date("Y-m-d", strtotime($row['date_created'])) : date("Y-m-d"); ?></td>
                        </tr>
                        <?php 
                                }
                            } else {
                        ?>
                        <tr>
                            <td>2</td>
                            <td>chairman</td>
                            <td>xyz</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View / Edit</button>
                            </td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td><?php echo date("Y-m-d"); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal & Action Handlers -->
<script>
    $('#new_member').click(function(){
        if (typeof uni_modal === 'function') {
            uni_modal('New Member', 'manage_user.php');
        } else {
            window.location.href = 'manage_user.php';
        }
    });

    $('.edit_user').click(function(){
        var id = $(this).attr('data-id');
        if (typeof uni_modal === 'function') {
            uni_modal('Edit Member', 'manage_user.php?id=' + id);
        } else {
            window.location.href = 'manage_user.php?id=' + id;
        }
    });

    $('.delete_user').click(function(){
        _conf("Are you sure to delete this member?", "delete_user", [$(this).attr('data-id')]);
    });

    function delete_user($id){
        if (typeof start_load === 'function') start_load();
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: { id: $id },
            success: function(resp){
                if(resp == 1){
                    if (typeof alert_toast === 'function') alert_toast("Data successfully deleted", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>