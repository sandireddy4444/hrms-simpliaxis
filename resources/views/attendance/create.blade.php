@extends('layouts.ap')

@section('title', 'Attendance')

@section('content')
<div class="container">
    {{-- Employee Info --}}
    <div id="employeeInfo" class="mb-3"></div>

    {{-- Search box --}}
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search by date (e.g., YYYY, YYYY-MM, YYYY-MM-DD)">
    </div>

    {{-- AJAX Buttons --}}
    <div class="mb-3">
        <button id="checkInBtn" class="btn btn-primary me-2">🟢 Check In</button>
        <button id="checkOutBtn" class="btn btn-secondary">🔴 Check Out</button>
    </div>

    {{-- Feedback Section --}}
    <div id="statusMessage" class="alert alert-info d-none alert-dismissible fade show" role="alert">
        <span id="statusText"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    {{-- Notifications Section --}}
    <div id="hoursNotification" class="alert alert-warning d-none alert-dismissible fade show" role="alert">
        <span id="hoursText"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Dynamic Attendance Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="attendanceTable">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody id="attendanceTableBody">
                {{-- Rows injected by AJAX --}}
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    // Show status messages
    function showStatusMessage(message, type = 'info') {
        const alertClass = `alert-${type}`;
        $('#statusMessage')
            .removeClass('d-none alert-info alert-danger alert-success alert-warning')
            .addClass(`alert ${alertClass}`)
            .find('#statusText')
            .text(message);
        setTimeout(() => {
            $('#statusMessage').addClass('d-none');
        }, 6000);
    }

    // Show hours notification
    function showHoursNotification(message) {
        if (message) {
            $('#hoursNotification')
                .removeClass('d-none')
                .addClass('alert alert-warning')
                .find('#hoursText')
                .text(message);
            setTimeout(() => {
                $('#hoursNotification').addClass('d-none');
            }, 2000);
        }
    }

    // Get current datetime in 'YYYY-MM-DD HH:mm:ss' format
    function getCurrentDateTime() {
        const now = new Date();
        const date = now.toISOString().slice(0, 10);
        const time = now.toTimeString().slice(0, 8);
        return `${date} ${time}`;
    }

    // Fetch attendance records (optionally filtered by date)
    function fetchAttendance(search = '') {
        $.ajax({
            url: '{{ route("attendance.fetch") }}',
            type: 'GET',
            data: { search: search },
            success: function(data) {
                // Display employee info
                $('#employeeInfo').html(`
                    <div class="card p-3">
                        <strong>Name:</strong> ${data.user.name}<br>
                        <strong>Email:</strong> ${data.user.email}
                    </div>
                `);

                // Clear previous table data
                $('#attendanceTableBody').empty();
                const today = new Date().toISOString().slice(0, 10);

                if (data.attendance.length === 0) {
                    $('#attendanceTableBody').append(`
                        <tr><td colspan="6" class="text-center">No attendance records found.</td></tr>
                    `);
                } else {
                    data.attendance.forEach(entry => {
                        const isToday = entry.date === today ? 'table-warning' : '';
                        $('#attendanceTableBody').append(`
                            <tr class="${isToday}">
                                <td>${entry.user?.name ?? '—'}</td>
                                <td>${entry.user?.email ?? '—'}</td>
                                <td>${entry.date}</td>
                                <td>${entry.check_in ?? '—'}</td>
                                <td>${entry.check_out ?? '—'}</td>
                                <td>${entry.total_hours}</td>
                            </tr>
                        `);
                        if (entry.notification) {
                            showHoursNotification(entry.notification);
                        }
                    });
                }
            },
            error: function() {
                showStatusMessage('Failed to load attendance data.', 'danger');
            }
        });
    }

    // Check-In button click
    $('#checkInBtn').click(function() {
        $('#checkInBtn, #checkOutBtn').prop('disabled', true);
        const checkInTime = getCurrentDateTime();

        $.post('{{ route("attendance.checkin") }}', { check_in: checkInTime })
            .done(function(response) {
                showStatusMessage(response.message, 'success');
                fetchAttendance($('#searchInput').val());
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.message || 'Error during check-in.';
                showStatusMessage(error, 'danger');
            })
            .always(function() {
                $('#checkInBtn, #checkOutBtn').prop('disabled', false);
            });
    });

    // Check-Out button click
    $('#checkOutBtn').click(function() {
        $('#checkInBtn, #checkOutBtn').prop('disabled', true);
        const checkOutTime = getCurrentDateTime();

        $.post('{{ route("attendance.checkout") }}', { check_out: checkOutTime })
            .done(function(response) {
                let message = response.message;
                if (response.notification) {
                    message += ` ${response.notification}`;
                }
                if (response.error) {
                    message += ` Error: ${response.error}`;
                }
                if (response.daily_total_hours !== undefined) {
                    message += ` Daily Total Hours: ${response.daily_total_hours}`;
                }
                showStatusMessage(message, response.status === 'success' ? 'success' : response.status === 'warning' ? 'warning' : 'danger');
                fetchAttendance($('#searchInput').val());
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.message || 'Error during check-out.';
                if (xhr.responseJSON?.error) {
                    error += ` Error: ${xhr.responseJSON.error}`;
                }
                showStatusMessage(error, 'danger');
            })
            .always(function() {
                $('#checkInBtn, #checkOutBtn').prop('disabled', false);
            });
    });

    // Dynamic search by date
    $('#searchInput').on('input', function() {
        const query = $(this).val().trim();
        fetchAttendance(query);
    });

    // Load data on page load
    fetchAttendance();
});
</script>
@endsection