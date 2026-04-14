<?php
// app/Notifications/LeaveRequestRejected.php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Leave Request Rejected')
            ->greeting('Hello ' . $notifiable->name . ',');
            
        $mail->line('Your leave request has been **rejected**.')
             ->line('**Leave Type:** ' . $this->getLeaveTypeName($this->leave->leave_type))
             ->line('**Period:** ' . $this->getFormattedPeriod($this->leave->start_date, $this->leave->end_date))
             ->line('**Total Days:** ' . $this->leave->total_days . ' day(s)');
             
        if ($this->leave->remarks) {
            $mail->line('**Reason for Rejection:** ' . $this->leave->remarks);
        } else {
            $mail->line('Please contact your supervisor for more information.');
        }
        
        $mail->action('View Leave Request', url('/leave/' . $this->leave->id))
             ->line('If you have any questions, please contact your supervisor.');
             
        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'leave_id' => $this->leave->id,
            'employee_number' => $this->leave->employee_number,
            'employee_name' => $this->leave->employee_name,
            'leave_type' => $this->leave->leave_type,
            'leave_type_name' => $this->getLeaveTypeName($this->leave->leave_type),
            'start_date' => $this->leave->start_date->format('Y-m-d'),
            'end_date' => $this->leave->end_date->format('Y-m-d'),
            'total_days' => $this->leave->total_days,
            'status' => $this->leave->status,
            'remarks' => $this->leave->remarks,
            'rejected_by' => $this->leave->rejected_by,
            'rejected_at' => $this->leave->rejected_at ? $this->leave->rejected_at->format('Y-m-d H:i:s') : null,
            'type' => 'leave_rejected',
            'message' => 'Your leave request has been rejected',
        ];
    }

    /**
     * Get leave type name in readable format
     */
    private function getLeaveTypeName($type)
    {
        $types = [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'study' => 'Study Leave',
            'unpaid' => 'Unpaid Leave',
            'emergency' => 'Emergency Leave',
            'compassionate' => 'Compassionate Leave',
            'other' => 'Other Leave',
        ];

        return $types[$type] ?? ucfirst($type);
    }

    /**
     * Get formatted period string
     */
    private function getFormattedPeriod($startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        }
        return 'N/A';
    }
}