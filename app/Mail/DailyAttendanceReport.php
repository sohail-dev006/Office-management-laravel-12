<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DailyAttendanceReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $attendances; 
    public $date;

    public function __construct(array $attendances, $date)
    {
        $this->attendances = $attendances;
        $this->date = $date;
    }

    public function build()
    {
        return $this->subject("Daily Attendance Report - {$this->date}")
                    ->view('emails.daily_attendance_report'); 
    }
}
