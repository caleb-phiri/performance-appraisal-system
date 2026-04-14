<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveRequestSubmitted extends Notification
{
    use Queueable;

    protected $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Your leave application has been submitted successfully',
            'leave_id' => $this->leave->id,
            'leave_type' => $this->leave->leave_type,
            'start_date' => $this->leave->start_date->format('d/m/Y'),
            'end_date' => $this->leave->end_date->format('d/m/Y'),
            'employee_number' => $this->leave->employee_number
        ];
    }
}