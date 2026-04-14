<?php

namespace App\Notifications;

use App\Models\Appraisal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PIPInitiatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appraisal;
    protected $supervisor;

    public function __construct(Appraisal $appraisal, User $supervisor)
    {
        $this->appraisal = $appraisal;
        $this->supervisor = $supervisor;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Performance Improvement Plan Initiated')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Your supervisor, ' . $this->supervisor->name . ', has initiated a Performance Improvement Plan based on your recent appraisal.')
            ->line('**Appraisal Period:** ' . $this->appraisal->period)
            ->line('**PIP Period:** ' . $this->appraisal->pip_start_date->format('M d, Y') . ' to ' . $this->appraisal->pip_end_date->format('M d, Y'))
            ->line('**Improvement Plan:**')
            ->line($this->appraisal->pip_plan)
            ->when($this->appraisal->pip_supervisor_notes, function ($mail) {
                return $mail->line('**Supervisor Notes:**')->line($this->appraisal->pip_supervisor_notes);
            })
            ->action('View Appraisal', url('/appraisals/' . $this->appraisal->id))
            ->line('Please schedule a meeting with your supervisor to discuss this plan in detail.')
            ->line('We are committed to supporting your professional growth.');
    }

    public function toArray($notifiable)
    {
        return [
            'appraisal_id' => $this->appraisal->id,
            'appraisal_period' => $this->appraisal->period,
            'supervisor_name' => $this->supervisor->name,
            'pip_start_date' => $this->appraisal->pip_start_date->format('Y-m-d'),
            'pip_end_date' => $this->appraisal->pip_end_date->format('Y-m-d'),
            'message' => 'A Performance Improvement Plan has been initiated for your appraisal.',
        ];
    }
}