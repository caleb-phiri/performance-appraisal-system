<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leave;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Make sure 'database' is included
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Leave Request Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your leave request has been **approved**.')
            ->line('**Leave Type:** ' . $this->getLeaveTypeName($this->leave->leave_type))
            ->line('**Period:** ' . $this->getFormattedPeriod($this->leave->start_date, $this->leave->end_date))
            ->line('**Total Days:** ' . $this->leave->total_days . ' day(s)')
            ->line('**Reason:** ' . $this->leave->reason)
            ->action('View Leave Request', url('/leave/' . $this->leave->id))
            ->line('Thank you for using our leave management system.');
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
            'type' => 'leave_approved',
            'message' => 'Your leave request has been approved',
            'icon' => 'check-circle',
            'color' => 'green'
        ];
    }

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

    private function getFormattedPeriod($startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        }
        return 'N/A';
    }
}