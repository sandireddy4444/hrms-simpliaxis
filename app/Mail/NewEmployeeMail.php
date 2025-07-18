<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEmployeeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employeeData;
    public $creatorName;

    public function __construct($employeeData, $creatorName)
    {
        $this->employeeData = $employeeData;
        $this->creatorName = $creatorName;
    }

    public function build()
    {
        return $this->subject('Welcome to Simpliaxis HRMS - Your Account Details')
                    ->view('emails.new-employee');
    }
}
