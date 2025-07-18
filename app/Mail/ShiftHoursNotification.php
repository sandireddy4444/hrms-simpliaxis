<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShiftHoursNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $date;
    public $hours;
    public $type;

    public function __construct($name, $date, $hours, $type)
    {
        $this->name = $name;
        $this->date = $date;
        $this->hours = $hours;
        $this->type = $type;
    }

    public function build()
    {
        $subject = match ($this->type) {
            'under-time' => 'Under-Time Daily Hours Notification',
            'overtime' => 'Overtime Daily Hours Notification',
            'auto-checkout' => 'Automatic Checkout Notification',
            default => 'Daily Hours Notification'
        };

        return $this->view('attendance.shift_hours_notification')
                    ->subject($subject)
                    ->with([
                        'name' => $this->name,
                        'date' => $this->date,
                        'hours' => $this->hours,
                        'type' => $this->type,
                    ]);
    }
}