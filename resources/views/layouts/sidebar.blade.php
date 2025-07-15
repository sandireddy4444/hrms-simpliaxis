```html
<div class="sidebar">
    <h4 class="mb-4">Simpliaxis HRMS</h4>
    <a href="#" class="users-link active">Users</a>
    <a href="#" class="logout-btn" onclick="event.preventDefault(); window.location.href='{{ route('logout') }}'">Logout</a>
    <style>
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
    </style>
</div>
```