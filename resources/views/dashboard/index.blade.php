
@extends('layouts.app')

@section('content')
    <div class="content">
        <div id="main-content">
            <!-- Add Employee Button (hidden for Employee) -->
            @if(in_array(Auth::user()->admin_type, ['Admin', 'SuperAdmin', 'HR Manager', 'Department Manager']))
                <button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#createUserModal" style="background-color: #3498DB; border-color: #3498DB;">Add Employee</button>
            @endif

            <!-- User table will load here (visible for all) -->
            <div id="users-table" class="table-responsive">
                <table id="usersTable" class="table table-striped">
                    <thead>
                        <tr>
                            @if(in_array(Auth::user()->admin_type, ['Admin', 'SuperAdmin', 'HR Manager', 'Department Manager']))
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

        <!-- Include Modals -->
        @include('users.create')
        @include('users.edit')
        @include('users.delete')
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table;
            var userRole = "{{ Auth::user()->admin_type }}";
            var isAdminOrSuperAdmin = ['SuperAdmin', 'Admin', 'HR Manager', 'Department Manager'].includes(userRole);

            function showMessage(message, type = 'info', duration = 3000) {
                const messageContainer = $('#message-container');
                messageContainer
                    .removeClass('alert-success alert-danger alert-info')
                    .addClass(`alert-${type}`)
                    .text(message)
                    .show();
                setTimeout(() => messageContainer.hide(), duration);
            }

            $('.users-link').on('click', function(e) {
                e.preventDefault();
                $('#main-content > div').hide();
                $('#users-table').show();

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

            $('.users-link').click();

            $('#createUserModal, #editUserModal').on('show.bs.modal', function() {
                $(this).find('.text-danger').hide().text('');
            });

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

            $(document).on('click', '.delete-user', function() {
                if (!isAdminOrSuperAdmin) {
                    showMessage('Only Admin, SuperAdmin, HR Manager, or Department Manager can delete users.', 'danger');
                    return;
                }
                var userId = $(this).data('id');
                $('#confirmDeleteBtn').data('id', userId);
                $('#deleteConfirmModal').modal('show');
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
@endsection
