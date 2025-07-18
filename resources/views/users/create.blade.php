
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="create_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="create_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="create_password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_phone_no" class="form-label">Phone No</label>
                        <input type="text" class="form-control" id="create_phone_no" name="phone_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_department" class="form-label">Department</label>
                        <select class="form-control" id="create_department" name="department" required>
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operations">Operations</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create_role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="create_role" name="role" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_date_of_joined" class="form-label">Date of Joined</label>
                        <input type="date" class="form-control" id="create_date_of_joined" name="date_of_joined" required>
                    </div>
                    <div class="mb-3">
                        <label for="create_is_active" class="form-label">Active</label>
                        <select class="form-control" id="create_is_active" name="is_active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create_admin_type" class="form-label">Admin Type</label>
                        <select class="form-control" id="create_admin_type" name="admin_type" required>
                            <option value="Admin">Admin</option>
                            <option value="SuperAdmin">SuperAdmin</option>
                            <option value="Employee">Employee</option>
                            <option value="HR Manager">HR Manager</option>
                            <option value="Department Manager">Department Manager</option>
                        </select>
                    </div>
                    <div id="create_error" class="text-danger" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #3498DB; border-color: #3498DB;">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>
