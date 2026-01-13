<?php

namespace App\Mail;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeAttendanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Attendance $attendance) {}


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Details'
        );
    }

 
    public function content(): Content
    {
        return new Content(
            view: 'emails.employee_attendance', 
            with: [
                'attendance' => $this->attendance
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
