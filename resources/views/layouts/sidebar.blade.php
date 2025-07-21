<div class="sidebar">
    <h4 class="mb-4">Simpliaxis HRMS</h4>
    <ul class="nav flex-column">
        @auth
            @if(in_array(Auth::user()->admin_type, ['Admin', 'SuperAdmin', 'HR Manager', 'Department Manager']))
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard') }}" class="nav-link users-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Users</a>
                </li>
            @endif
            <li class="nav-item mb-2">
                <a href="{{ route('attendance.create') }}" class="nav-link {{ request()->routeIs('attendance.create') ? 'active' : '' }}">Attendance</a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('attendance.dashboard') }}" class="nav-link {{ request()->routeIs('attendance.dashboard') ? 'active' : '' }}">Attendance Report</a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link {{ request()->routeIs('logout') ? 'active' : '' }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        @else
            <li class="nav-item mb-2">
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
            </li>
        @endauth
    </ul>
</div>

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
</style>

<script>
$(document).ready(function() {
    $('.sidebar a.nav-link').on('click', function() {
        $('.sidebar a').removeClass('active');
        $(this).addClass('active');
    });
});
</script>