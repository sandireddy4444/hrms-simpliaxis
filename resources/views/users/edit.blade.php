
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (leave blank to keep unchanged)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone_no" class="form-label">Phone No</label>
                        <input type="text" class="form-control" id="edit_phone_no" name="phone_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_department" class="form-label">Department</label>
                        <select class="form-control" id="edit_department" name="department" required>
                            <option value="HR">HR</option>
                            <option value="IT">IT</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operations">Operations</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="edit_role" name="role" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_date_of_joined" class="form-label">Date of Joined</label>
                        <input type="date" class="form-control" id="edit_date_of_joined" name="date_of_joined" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_is_active" class="form-label">Active</label>
                        <select class="form-control" id="edit_is_active" name="is_active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_admin_type" class="form-label">Admin Type</label>
                        <select class="form-control" id="edit_admin_type" name="admin_type" required>
                            <option value="Admin">Admin</option>
                            <option value="SuperAdmin">SuperAdmin</option>
                            <option value="Employee">Employee</option>
                        </select>
                    </div>
                    <div id="edit_error" class="text-danger" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #3498DB; border-color: #3498DB;">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
