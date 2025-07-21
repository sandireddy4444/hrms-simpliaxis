<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpliaxis HRMS - @yield('title')</title>

    {{-- App & Bootstrap Styles --}}
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
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            color: white;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #f8f9fa;
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
        .add-btn {
            margin-bottom: 15px;
        }
    </style>

    @yield('styles')
</head>
<body>

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <div class="content">
        <h2 class="mb-4">@yield('title')</h2>
        @yield('content')
    </div>
    <div class="sidebar">
    <h4 class="mb-4">Attendance App</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
           <a href="{{ route('attendance.create') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                AttendanceHere
            </a>
        </li>
        <li class="nav-item mb-2">
             <a href="{{ route('attendance.dashboard') }}" class="nav-link {{ request()->routeIs('attendance.dashboard') ? 'active' : '' }}">
                AttendanceReport
            </a>
            
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                login here
            </a>
        </li>
       <li class="nav-item mb-2">
            <a href="{{ route('logout') }}" class="nav-link {{ request()->routeIs('logout') ? 'active' : '' }}">
                logout
            </a>
        </li>
    </ul>
</div>


    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
