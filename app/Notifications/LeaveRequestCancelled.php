<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;

    /**
     * Create a new notification instance.
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your leave request has been cancelled',
            'leave_id' => $this->leave->id,
            'leave_type' => $this->leave->leave_type,
            'start_date' => $this->leave->start_date->format('d/m/Y'),
            'end_date' => $this->leave->end_date->format('d/m/Y'),
            'total_days' => $this->leave->total_days,
            'status' => 'cancelled',
            'employee_number' => $this->leave->employee_number,
            'employee_name' => $this->leave->employee_name,
            'cancelled_at' => now()->format('d/m/Y H:i:s'),
            'icon' => 'ban',
            'color' => 'gray',
        ];
    }
}