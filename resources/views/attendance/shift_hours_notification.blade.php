<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $type === 'under-time' ? 'Under-Time Notification' : ($type === 'overtime' ? 'Overtime Notification' : 'Automatic Checkout Notification') }}</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    @if($type === 'under-time')
        <p>Your total work hours on {{ $date }} were {{ $hours }} hours, which is less than the required 9 hours.</p>
        <p>Please ensure you complete the required hours or contact your supervisor for clarification.</p>
    @elseif($type === 'overtime')
        <p>Your total work hours on {{ $date }} were {{ $hours }} hours, which exceeds the required 9 hours.</p>
        <p>Thank you for your extra effort!</p>
    @else
        <p>You forgot to check out on {{ $date }}. An automatic checkout was performed after 9 hours.</p>
        <p>Your total work hours for the day were recorded as {{ $hours }} hours.</p>
    @endif
    <p>Best regards,<br>simpliaxis</p>
</body>
</html>