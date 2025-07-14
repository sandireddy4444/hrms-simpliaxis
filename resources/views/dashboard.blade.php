<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpliaxis HRMS - Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
      body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #dc3545;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            color: #ECF0F1;
            transition: all 0.3s;
        }
        .sidebar h4 {
            color: #000000;
            background-color: #FFFFFF;
            text-align: center;
            margin-bottom: 30px;
            padding: 10px 0;
        }
        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            color: #ECF0F1;
            display: block;
            font-size: 16px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #3498DB;
            color: #FFFFFF;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background-color: #FFFFFF;
        }
        .table-responsive {
            background: #FFFFFF;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .modal-content {
            border-radius: 10px;
        }
        .logout-btn {
            position: absolute;
            bottom: 20px;
            width: 50%;
            left: 5%;
            background-color: #dc3545;
            color: #FFFFFF;
        }
        .logout-btn:hover {
            background-color: #dc3545;
            color: #FFFFFF;
        }
        .add-btn {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="mb-4">Simpliaxis HRMS</h4>
        <a href="#" class="users-link active">Users</a>
        <a href="#" class="logout-btn" onclick="event.preventDefault(); window.location.href='{{ route('logout') }}'">Logout</a>
    </div>

    <div class="content">
        <div id="main-content">
            <!-- Add Employee Button -->
            <button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#createUserModal" style="background-color: #3498DB; border-color: #3498DB;">Add Employee</button>

            <!-- User table will load here -->
            <div id="users-table" class="table-responsive" style="display: none;">
                <table id="usersTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Date of Joined</th>
                            <th>Active</th>
                            <th>Admin Type</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- Message Container -->
        <div id="message-container" class="alert alert-info" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 1000; max-width: 300px;"></div>
    </div>

    <!-- Create User Modal -->
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

    <!-- Edit User Modal -->
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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table;
            var isSuperAdmin = "{{ Auth::user()->admin_type }}" === "SuperAdmin"; // Blade variable check

            function showMessage(message, type = 'info', duration = 3000) {
                const messageContainer = $('#message-container');
                messageContainer
                    .removeClass('alert-success alert-danger alert-info')
                    .addClass(`alert-${type}`)
                    .text(message)
                    .show();
                setTimeout(() => messageContainer.hide(), duration);
            }

            // Load Users table on click
            $('.users-link').on('click', function(e) {
                e.preventDefault();
                $('#main-content > div').hide();
                $('#users-table').show();

                if (!table) {
                    table = $('#usersTable').DataTable({
                        ajax: '{{ route("users.data") }}',
                        columns: [
                            {
                                data: null,
                                render: function(data, type, row) {
                                    var actions = '<button class="btn btn-sm btn-warning edit-user" data-id="' + row.id + '" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>';
                                    if (isSuperAdmin) {
                                        actions += ' <button class="btn btn-sm btn-danger delete-user" data-id="' + row.id + '" style="background-color: #E74C3C; border-color: #E74C3C;">Delete</button>';
                                    }
                                    return actions;
                                }
                            },
                            { data: 'id' },
                            { data: 'name' },
                            { data: 'email' },
                            { data: 'phone_no' },
                            { data: 'department' },
                            { data: 'role' },
                            { data: 'date_of_joined' },
                            { data: 'is_active', render: function(data) { return data ? 'Active' : 'Inactive'; } },
                            { data: 'admin_type' }
                        ],
                        pageLength: 10,
                        order: [[0, 'asc']]
                    });
                } else {
                    table.ajax.reload();
                }

                $('.sidebar a').removeClass('active');
                $(this).addClass('active');
            });

            // Initialize table on page load
            $('.users-link').click();

            // Show event for modals to reset error state
            $('#createUserModal, #editUserModal').on('show.bs.modal', function() {
                $(this).find('.text-danger').hide().text('');
            });

            // Create User
            $('#createUserForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize() + '&_token={{ csrf_token() }}';
                var password = $('#create_password').val();
                $('#create_error').hide().text('');

                if (password && password.length < 8) {
                    $('#create_error').text('Password must be at least 8 characters.').show();
                    return;
                }

                $.ajax({
                    url: '{{ route("users.store") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#createUserModal').modal('hide');
                            table.ajax.reload();
                            showMessage(response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        $('#create_error').text(xhr.responseJSON.message || 'An error occurred.').show();
                        if (xhr.responseJSON.errors && xhr.responseJSON.errors.password) {
                            $('#create_error').text(xhr.responseJSON.errors.password[0]).show();
                        }
                    }
                });
            });

            // Edit User - Populate Form
            $(document).on('click', '.edit-user', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ url("users") }}/' + id + '/edit',
                    method: 'GET',
                    success: function(data) {
                        $('#edit_id').val(data.id);
                        $('#edit_name').val(data.name);
                        $('#edit_email').val(data.email);
                        $('#edit_phone_no').val(data.phone_no);
                        $('#edit_department').val(data.department);
                        $('#edit_role').val(data.role);
                        $('#edit_date_of_joined').val(data.date_of_joined);
                        $('#edit_is_active').val(data.is_active ? '1' : '0');
                        $('#edit_admin_type').val(data.admin_type);
                        $('#edit_error').hide().text('');
                    },
                    error: function(xhr) {
                        $('#edit_error').text(xhr.responseJSON.message || 'An error occurred.').show();
                    }
                });
            });

            // Update User
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize() + '&_token={{ csrf_token() }}&_method=PUT';
                var id = $('#edit_id').val();
                var password = $('#edit_password').val();
                $('#edit_error').hide().text('');

                if (password && password.length < 8) {
                    $('#edit_error').text('Password must be at least 8 characters.').show();
                    return;
                } else if (!password) {
                    formData = formData.replace(/&password=[^&]*/g, '');
                }

                $.ajax({
                    url: '{{ url("users") }}/' + id,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#editUserModal').modal('hide');
                            table.ajax.reload();
                            showMessage(response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = xhr.responseJSON.message || 'An error occurred.';
                        if (xhr.responseJSON.errors && xhr.responseJSON.errors.password) {
                            $('#edit_error').text(xhr.responseJSON.errors.password[0]).show();
                        } else {
                            $('#edit_error').text(errorMessage).show();
                        }
                    }
                });
            });

            // Delete User
            $(document).on('click', '.delete-user', function() {
                if (!isSuperAdmin) {
                    showMessage('Only SuperAdmin can delete users.', 'danger');
                    return;
                }
                if (confirm('Are you sure you want to delete this user?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '{{ url("users") }}/' + id,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                showMessage(response.message, 'success');
                            }
                        },
                        error: function(xhr) {
                            showMessage(xhr.responseJSON.message || 'An error occurred.', 'danger');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>