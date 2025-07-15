<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpliaxis HRMS - Dashboard</title>
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
            <!-- Add Employee Button (hidden for Employee) -->
            @if(Auth::user()->admin_type !== 'Employee')
                <button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#createUserModal" style="background-color: #3498DB; border-color: #3498DB;">Add Employee</button>
            @endif

            <!-- User table will load here (visible for all) -->
            <div id="users-table" class="table-responsive">
                <table id="usersTable" class="table table-striped">
                    <thead>
                        <tr>
                            @if(Auth::user()->admin_type !== 'Employee')
                                <th>Actions</th>
                            @endif
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this user? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" style="background-color: #E74C3C; border-color: #E74C3C;">Delete</button>
                </div>
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
            var userRole = "{{ Auth::user()->admin_type }}"; // Get user role
            var isAdminOrSuperAdmin = userRole === "SuperAdmin" || userRole === "Admin"; // Check for delete privilege

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
                $('#users-table').show(); // Ensure table is visible for all

                if (!table) {
                    let columns = [];

                    if (isAdminOrSuperAdmin) {
                        columns.push({
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-sm btn-warning edit-user" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-user" data-id="${row.id}" style="background-color: #E74C3C; border-color: #E74C3C;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">Delete</button>
                                `;
                            }
                        });
                    }

                    // Push all common columns (for everyone)
                    columns = columns.concat([
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'phone_no' },
                        { data: 'department' },
                        { data: 'role' },
                        { data: 'date_of_joined' },
                        { data: 'is_active', render: function(data) { return data ? 'Active' : 'Inactive'; } },
                        { data: 'admin_type' }
                    ]);

                    // Initialize the table
                    table = $('#usersTable').DataTable({
                        ajax: {
                            url: '{{ route("users.data") }}',
                            dataSrc: ''
                        },
                        columns: columns,
                        pageLength: 10,
                        order: [[0, 'asc']]
                    });
                } else {
                    table.ajax.reload();
                }

                $('.sidebar a').removeClass('active');
                $(this).addClass('active');
            });

            // Initialize table on page load for all users
            $('.users-link').click();

            // Show event for modals to reset error state
            $('#createUserModal, #editUserModal').on('show.bs.modal', function() {
                $(this).find('.text-danger').hide().text('');
            });

            // Create User
            $('#createUserForm').on('submit', function(e) {
    e.preventDefault();
    $('#create_error').hide().text('');

    const email = $('#create_email').val();
    const phone = $('#create_phone_no').val();
    const name = $('#create_name').val();
    const password = $('#create_password').val();

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phonePattern = /^\d{10}$/;
    const namePattern = /^[a-zA-Z\s]+$/;

    if (!namePattern.test(name)) {
        $('#create_error').text('Name must contain only letters and spaces.').show();
        return;
    }

    if (!emailPattern.test(email)) {
        $('#create_error').text('Invalid email format.').show();
        return;
    }

    if (!phonePattern.test(phone)) {
        $('#create_error').text('Phone number must be exactly 10 digits.').show();
        return;
    }

    if (password.length < 8) {
        $('#create_error').text('Password must be at least 8 characters.').show();
        return;
    }

    const formData = $(this).serialize() + '&_token={{ csrf_token() }}';

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
    $('#edit_error').hide().text('');

    const email = $('#edit_email').val();
    const phone = $('#edit_phone_no').val();
    const name = $('#edit_name').val();
    const password = $('#edit_password').val();

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phonePattern = /^\d{10}$/;
    const namePattern = /^[a-zA-Z\s]+$/;

    if (!namePattern.test(name)) {
        $('#edit_error').text('Name must contain only letters and spaces.').show();
        return;
    }

    if (!emailPattern.test(email)) {
        $('#edit_error').text('Invalid email format.').show();
        return;
    }

    if (!phonePattern.test(phone)) {
        $('#edit_error').text('Phone number must be exactly 10 digits.').show();
        return;
    }

    if (password && password.length < 8) {
        $('#edit_error').text('Password must be at least 8 characters.').show();
        return;
    }

    let formData = $(this).serialize() + '&_token={{ csrf_token() }}&_method=PUT';
    if (!password) {
        formData = formData.replace(/&password=[^&]*/g, '');
    }

    const id = $('#edit_id').val();

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
            $('#edit_error').text(xhr.responseJSON.message || 'An error occurred.').show();
        }
    });
});


            // Delete Confirmation and Action
            $(document).on('click', '.delete-user', function() {
                if (!isAdminOrSuperAdmin) {
                    showMessage('Only Admin or SuperAdmin can delete users.', 'danger');
                    return;
                }
                var userId = $(this).data('id');
                $('#confirmDeleteBtn').data('id', userId); // Store the user ID in the confirm button
                $('#deleteConfirmModal').modal('show'); // Show the modal
            });

            $('#confirmDeleteBtn').on('click', function() {
                var userId = $(this).data('id');
                $.ajax({
                    url: '{{ url("users") }}/' + userId,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteConfirmModal').modal('hide');
                            table.ajax.reload();
                            showMessage(response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        showMessage(xhr.responseJSON.message || 'An error occurred.', 'danger');
                        $('#deleteConfirmModal').modal('hide');
                    }
                });
            });
        });
    </script>
</body>
</html>