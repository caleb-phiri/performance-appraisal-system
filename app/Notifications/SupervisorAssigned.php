<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupervisorAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $employee;

    public function __construct(User $employee)
    {
        $this->employee = $employee;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Team Member Assigned')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned as the supervisor for:')
            ->line('**Name:** ' . $this->employee->name)
            ->line('**Employee ID:** ' . $this->employee->employee_number)
            ->line('**Department:** ' . ($this->employee->department ?? 'N/A'))
            ->line('**Job Title:** ' . ($this->employee->job_title ?? 'N/A'))
            ->action('View Employee Profile', url('/profile?employee=' . $this->employee->employee_number))
            ->line('You will now receive approval requests for this employee\'s leave and appraisal requests.');
    }

    public function toArray($notifiable)
    {
        return [
            'employee_number' => $this->employee->employee_number,
            'employee_name' => $this->employee->name,
            'department' => $this->employee->department,
            'job_title' => $this->employee->job_title,
            'type' => 'supervisor_assigned',
            'message' => 'You have been assigned as supervisor for ' . $this->employee->name,
        ];
    }
}