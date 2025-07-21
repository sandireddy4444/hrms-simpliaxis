<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Mail\ShiftHoursNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AttendanceController extends Controller
{
    public function create()
    {
        return view('attendance.create');
    }

    public function dashboardView()
    {
        return view('attendance.dashboard');
    }

    public function dashboardData(Request $request)
    {
        $user = Auth::user();
        $dateFilter = $request->query('dateFilter', '');
        $query = Attendance::with('user');

        if ($user->admin_type === 'Employee') {
            $query->where('user_id', $user->id);
        }

        if ($dateFilter) {
            $query->where('date', 'like', $dateFilter . '%');
        } else {
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
            $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }

        $attendance = $query->orderBy('date', 'desc')
                           ->orderBy('check_in', 'desc')
                           ->get(['id', 'user_id', 'date', 'check_in', 'check_out', 'total_hours']);

        $dailyTotals = $attendance->groupBy('date')->map(function ($records, $date) {
            $totalHours = $records->sum('total_hours');
            $notification = null;

            if ($totalHours > 0) {
                if ($totalHours < 9) {
                    $notification = "Under-time: You worked $totalHours hours on $date, which is less than the required 9 hours.";
                } elseif ($totalHours > 9) {
                    $notification = "Overtime: You worked $totalHours hours on $date, which is more than the required 9 hours.";
                }
            }

            return [
                'total_hours' => $totalHours ?: 0,
                'notification' => $notification,
            ];
        });

        $mostRecentDate = $attendance->max('date') ?? Carbon::today()->toDateString();

        $formattedAttendance = $attendance->map(function ($record) use ($dailyTotals) {
            $user = $record->user;
            return [
                'user_id' => $record->user_id,
                'name' => $user->name ?? '—',
                'email' => $user->email ?? '—',
                'date' => $record->date,
                'check_in' => $record->check_in ?? '—',
                'check_out' => $record->check_out ?? '—',
                'total_hours' => $record->total_hours ?: '—',
                'notification' => $dailyTotals[$record->date]['notification'] ?? null,
            ];
        })->values();

        return response()->json([
            'data' => $formattedAttendance,
            'daily_totals' => $dailyTotals,
            'most_recent_date' => $mostRecentDate,
        ]);
    }

    public function fetch()
    {
        $user = Auth::user();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->orderBy('check_in', 'desc')
            ->get(['id', 'date', 'check_in', 'check_out', 'total_hours']);

        $dailyTotals = $attendance->groupBy('date')->map(function ($records, $date) {
            $totalHours = $records->sum('total_hours');
            $notification = null;

            if ($totalHours > 0) {
                if ($totalHours < 9) {
                    $notification = "Under-time: You worked $totalHours hours on $date, which is less than the required 9 hours.";
                } elseif ($totalHours > 9) {
                    $notification = "Overtime: You worked $totalHours hours on $date, which is more than the required 9 hours.";
                }
            }

            return [
                'total_hours' => $totalHours ?: 0,
                'notification' => $notification,
            ];
        });

        $formattedAttendance = $attendance->map(function ($record) use ($user, $dailyTotals) {
            return [
                'id' => $record->id,
                'date' => $record->date,
                'check_in' => $record->check_in,
                'check_out' => $record->check_out,
                'total_hours' => $record->total_hours ?: '—',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'notification' => $dailyTotals[$record->date]['notification'] ?? null,
            ];
        })->values();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'attendance' => $formattedAttendance,
            'daily_totals' => $dailyTotals,
        ]);
    }

    public function getData()
    {
        $records = Attendance::orderBy('date', 'desc')->get();
        return response()->json($records);
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();

        Log::info('Check-in initiated for user ID: ' . $user->id . ', Date: ' . $today);

        $latest = Attendance::where('user_id', $user->id)
                            ->where('date', $today)
                            ->latest('id')
                            ->first();

        if ($latest && $latest->check_in && !$latest->check_out) {
            Log::error('Pending check-in found for user ID: ' . $user->id . ', Date: ' . $today);
            return response()->json([
                'message' => 'You must check out before checking in again.',
                'status' => 'error'
            ], 400);
        }

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'date' => $today,
            'check_in' => $now->format('Y-m-d H:i:s'),
        ]);

        Log::info('Check-in successful for user ID: ' . $user->id . ', Record ID: ' . $attendance->id);

        Session::flash('daily_total_date', $today);

        return response()->json([
            'message' => 'Checked in successfully.',
            'status' => 'success'
        ]);
    }



public function checkOut(Request $request)
{
    $user = Auth::user();
    $now = Carbon::now();
    $today = $now->toDateString();

    Log::info('Check-out initiated for user ID: ' . $user->id . ', Date: ' . $today);

    $latest = Attendance::where('user_id', $user->id)
                        ->where('date', $today)
                        ->whereNull('check_out')
                        ->latest('id')
                        ->first();

    if (!$latest) {
        Log::error('No pending check-in found for user ID: ' . $user->id . ', Date: ' . $today);
        return response()->json([
            'message' => 'No pending check-in found for today.',
            'status' => 'error'
        ], 400);
    }

    $checkIn = Carbon::parse($latest->check_in);
    $checkOut = $now;
    $totalHours = $checkIn->diffInMinutes($checkOut) / 60;

    Log::info('Check-out for record ID: ' . $latest->id . ', Shift hours: ' . $totalHours . ', Check-in: ' . $latest->check_in . ', Check-out: ' . $now);

    $latest->update([
        'check_out' => $now->format('Y-m-d H:i:s'),
        'total_hours' => round($totalHours, 2),
    ]);

    // Calculate daily total hours
    $dailyTotalHours = Attendance::where('user_id', $user->id)
                                 ->where('date', $today)
                                 ->whereNotNull('total_hours')
                                 ->sum('total_hours');
    $dailyTotalHours = round($dailyTotalHours, 2);

    Log::info('Daily total hours for user ID: ' . $user->id . ', Date: ' . $today . ': ' . $dailyTotalHours);

    $email = $latest->email ?? $user->email;

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Log::error('Invalid or missing email for user ID: ' . $user->id . ', Record ID: ' . $latest->id . ', Email: ' . ($email ?? 'null'));
        return response()->json([
            'message' => 'Checked out successfully, but no valid email available for notification.',
            'status' => 'warning',
            'error' => 'Invalid or missing email address.',
            'daily_total_hours' => $dailyTotalHours
        ], 200);
    }

    // Debug mail configuration
    Log::info('Mail Configuration Debug', [
        'default_mailer' => config('mail.default'),
        'mailer' => config('mail.mailer'),
        'host' => config('mail.mailers.smtp.host'),
        'port' => config('mail.mailers.smtp.port'),
        'username' => config('mail.mailers.smtp.username'),
        'encryption' => config('mail.mailers.smtp.encryption'),
        'from_address' => config('mail.from.address'),
    ]);

    // Check if log driver is being used
    if (config('mail.default') === 'log' || config('mail.mailer') === 'log') {
        Log::warning('Mail driver is set to log, emails will not be sent');
        return response()->json([
            'message' => 'Checked out successfully, but emails are not sent (log driver in use).',
            'status' => 'warning',
            'daily_total_hours' => $dailyTotalHours,
            'email' => $email
        ], 200);
    }

    $notificationStatus = 'No notification needed (daily total hours are exactly 9).';
    $notificationSent = false;

    try {
        Log::info('SMTP Configuration for check-out notification', [
            'mailer' => config('mail.mailer'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
            'encryption' => config('mail.mailers.smtp.encryption')
        ]);

        if ($dailyTotalHours < 9) {
            Log::info('Preparing under-time notification to: ' . $email . ' for daily total hours: ' . $dailyTotalHours);
            $mailable = new ShiftHoursNotification(
                $latest->name ?? $user->name,
                $today,
                $dailyTotalHours,
                'under-time'
            );
            $emailContent = $mailable->render();
            Log::info('Under-time email content', ['content' => $emailContent]);
            Mail::to($email)->send($mailable);
            Log::info('Under-time notification sent to: ' . $email . ', Subject: ' . $mailable->subject);
            $notificationStatus = 'Under-time notification sent to ' . $email . ' for daily total of ' . $dailyTotalHours . ' hours.';
            $notificationSent = true;
        } elseif ($dailyTotalHours > 9) {
            Log::info('Preparing overtime notification to: ' . $email . ' for daily total hours: ' . $dailyTotalHours);
            $mailable = new ShiftHoursNotification(
                $latest->name ?? $user->name,
                $today,
                $dailyTotalHours,
                'overtime'
            );
            $emailContent = $mailable->render();
            Log::info('Overtime email content', ['content' => $emailContent]);
            Mail::to($email)->send($mailable);
            Log::info('Overtime notification sent to: ' . $email . ', Subject: ' . $mailable->subject);
            $notificationStatus = 'Overtime notification sent to ' . $email . ' for daily total of ' . $dailyTotalHours . ' hours.';
            $notificationSent = true;
        }
    } catch (\Exception $e) {
        Log::error('Failed to send notification for user ID: ' . $user->id . ', Date: ' . $today, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'smtp_config' => [
                'mailer' => config('mail.mailer'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
            ]
        ]);
        return response()->json([
            'message' => 'Checked out successfully, but failed to send notification.',
            'status' => 'warning',
            'error' => 'Email sending failed: ' . $e->getMessage(),
            'daily_total_hours' => $dailyTotalHours
        ], 200);
    }

    Session::flash('daily_total_hours', $dailyTotalHours);
    Session::flash('daily_total_date', $today);

    return response()->json([
        'message' => 'Checked out successfully.',
        'status' => $notificationSent ? 'success' : 'info',
        'notification' => $notificationStatus,
        'shift_hours' => round($totalHours, 2),
        'daily_total_hours' => $dailyTotalHours,
        'email' => $email
    ]);
}
    public function sendNotification(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            Log::error('Attendance record not found for ID: ' . $id);
            return response()->json([
                'message' => 'Attendance record not found.',
                'status' => 'error'
            ], 404);
        }

        $user = Auth::user();
        $email = $attendance->email ?? $user->email;

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::error('Invalid or missing email for record ID: ' . $id . ', Email: ' . ($email ?? 'null'));
            return response()->json([
                'message' => 'No valid email available for notification.',
                'status' => 'error',
                'error' => 'Invalid or missing email address.'
            ], 400);
        }

        $dailyTotalHours = Attendance::where('user_id', $user->id)
                                     ->where('date', $attendance->date)
                                     ->whereNotNull('total_hours')
                                     ->sum('total_hours');
        $dailyTotalHours = round($dailyTotalHours, 2);

        $notificationStatus = 'No notification needed (daily total hours are exactly 9).';
        $notificationSent = false;

        try {
            Log::info('SMTP Configuration for manual notification', [
                'mailer' => config('mail.mailer'),
                'host' => config('mail.host'),
                'port' => config('mail.port'),
                'username' => config('mail.username'),
                'from_address' => config('mail.from.address'),
                'encryption' => config('mail.encryption')
            ]);

            if ($dailyTotalHours < 9) {
                Log::info('Preparing under-time notification to: ' . $email . ' for daily total hours: ' . $dailyTotalHours);
                $mailable = new ShiftHoursNotification(
                    $attendance->name ?? $user->name,
                    $attendance->date,
                    $dailyTotalHours,
                    'under-time'
                );
                $emailContent = $mailable->render();
                Log::info('Under-time email content', ['content' => $emailContent]);
                Mail::to($email)->send($mailable);
                Log::info('Under-time notification sent to: ' . $email . ', Subject: ' . $mailable->subject);
                $notificationStatus = 'Under-time notification sent to ' . $email . ' for daily total of ' . $dailyTotalHours . ' hours.';
                $notificationSent = true;
            } elseif ($dailyTotalHours > 9) {
                Log::info('Preparing overtime notification to: ' . $email . ' for daily total hours: ' . $dailyTotalHours);
                $mailable = new ShiftHoursNotification(
                    $attendance->name ?? $user->name,
                    $attendance->date,
                    $dailyTotalHours,
                    'overtime'
                );
                $emailContent = $mailable->render();
                Log::info('Overtime email content', ['content' => $emailContent]);
                Mail::to($email)->send($mailable);
                Log::info('Overtime notification sent to: ' . $email . ', Subject: ' . $mailable->subject);
                $notificationStatus = 'Overtime notification sent to ' . $email . ' for daily total of ' . $dailyTotalHours . ' hours.';
                $notificationSent = true;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notification for record ID: ' . $id . ', Error: ' . $e->getMessage() . ', Trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Failed to send notification.',
                'status' => 'error',
                'error' => 'Email sending failed: ' . $e->getMessage(),
                'daily_total_hours' => $dailyTotalHours
            ], 500);
        }

        return response()->json([
            'message' => $notificationStatus,
            'status' => $notificationSent ? 'success' : 'info',
            'daily_total_hours' => $dailyTotalHours,
            'email' => $email
        ]);
    }
}