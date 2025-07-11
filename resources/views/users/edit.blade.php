<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Simpliaxis HRMS</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Edit User</h3>
        <form id="editUserForm" action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (leave blank to keep unchanged)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="phone_no" class="form-label">Phone No</label>
                <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $user->phone_no }}" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="HR" {{ $user->department == 'HR' ? 'selected' : '' }}>HR</option>
                    <option value="IT" {{ $user->department == 'IT' ? 'selected' : '' }}>IT</option>
                    <option value="Finance" {{ $user->department == 'Finance' ? 'selected' : '' }}>Finance</option>
                    <option value="Marketing" {{ $user->department == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="Operations" {{ $user->department == 'Operations' ? 'selected' : '' }}>Operations</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <input type="text" class="form-control" id="role" name="role" value="{{ $user->role }}" required>
            </div>
            <div class="mb-3">
                <label for="date_of_joined" class="form-label">Date of Joined</label>
                <input type="date" class="form-control" id="date_of_joined" name="date_of_joined" value="{{ $user->date_of_joined }}" required>
            </div>
            <div class="mb-3">
                <label for="is_active" class="form-label">Active</label>
                <select class="form-control" id="is_active" name="is_active" required>
                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="admin_type" class="form-label">Admin Type</label>
                <select class="form-control" id="admin_type" name="admin_type" required>
                    <option value="Admin" {{ $user->admin_type == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="SuperAdmin" {{ $user->admin_type == 'SuperAdmin' ? 'selected' : '' }}>SuperAdmin</option>
                    <option value="Employee" {{ $user->admin_type == 'Employee' ? 'selected' : '' }}>Employee</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>