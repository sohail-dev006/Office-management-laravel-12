<?php

namespace App\Mail;

use App\Models\Salary;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalarySlipMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Salary $salary;

    public function __construct(Salary $salary)
    {

        $this->salary = $salary->load('employee');
    }

    public function build()
    {
        $pdf = Pdf::loadView('salary.pdf', [
            'salary' => $this->salary
        ]);

        return $this->subject('Your Salary Slip')
            ->view('emails.salary-slip') 
            ->with([
                'salary' => $this->salary
            ])
            ->attachData(
                $pdf->output(),
                'salary-slip-'.$this->salary->month.'-'.$this->salary->year.'.pdf',
                [
                    'mime' => 'application/pdf'
                ]
            );
    }
}
