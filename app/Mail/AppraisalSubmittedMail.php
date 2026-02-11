<?php

namespace App\Mail;

use App\Models\Appraisal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppraisalSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appraisal;

    public function __construct(Appraisal $appraisal)
    {
        $this->appraisal = $appraisal;
    }

    public function build()
    {
        return $this->subject('New Performance Appraisal Submission')
                    ->markdown('emails.appraisal-submitted')
                    ->with([
                        'employeeName' => $this->appraisal->employee->name,
                        'periodName' => $this->appraisal->period->name,
                        'reviewUrl' => route('appraisals.review', $this->appraisal->id)
                    ]);
    }
}
