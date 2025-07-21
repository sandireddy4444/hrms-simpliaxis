@extends('layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="container mt-4">
    <div class="table-responsive">
        <table id="dashboardAttendanceTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    @if(Auth::user()->admin_type !== 'Employee')
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    @endif
                    <th>Date</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    const isEmployee = "{{ Auth::user()->admin_type }}" === "Employee";

    const table = $('#dashboardAttendanceTable').DataTable({
        ajax: '{{ route("attendance.dashboard.data") }}',
        columns: [
            @if(Auth::user()->admin_type !== 'Employee')
            { data: 'user_id' },
            { data: 'name' },
            { data: 'email' },
            @endif
            { data: 'date' },
            { data: 'check_in' },
            { data: 'check_out' },
            { data: 'total_hours' }
        ],
        order: [[@if(Auth::user()->admin_type !== 'Employee') 3 @else 0 @endif, 'desc']],
        pageLength: 10
    });
});
</script>
@endsection