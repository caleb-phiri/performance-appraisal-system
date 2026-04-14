<?php

namespace App\Notifications;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestPending extends Notification implements ShouldQueue
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
        $employee = $this->leave->employee;
        $chain = $employee->getApprovalChain();
        $currentLevel = $this->leave->current_approval_level;
        $currentApprover = $chain[$currentLevel - 1]['user'] ?? null;
        
        $levelName = match($this->leave->current_approval_level) {
            1 => 'Direct Supervisor',
            2 => 'Manager',
            3 => 'Final Approver',
            default => 'Approver'
        };
        
        return (new MailMessage)
            ->subject('Leave Request Pending Your Approval')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A leave request requires your approval.')
            ->line('**Employee:** ' . ($employee->name ?? 'N/A'))
            ->line('**Employee ID:** ' . ($employee->employee_number ?? 'N/A'))
            ->line('**Leave Type:** ' . $this->leave->getLeaveTypeNameAttribute())
            ->line('**Period:** ' . $this->leave->getFormattedPeriodAttribute())
            ->line('**Total Days:** ' . $this->leave->duration_in_words)
            ->line('**Reason:** ' . $this->leave->reason)
            ->line('**Your Role:** ' . $levelName)
            ->action('Review Leave Request', url('/supervisor/leaves/' . $this->leave->id))
            ->line('Thank you for your prompt attention to this request.');
    }

    public function toArray($notifiable)
    {
        return [
            'leave_id' => $this->leave->id,
            'employee_number' => $this->leave->employee_number,
            'employee_name' => $this->leave->employee_name,
            'leave_type' => $this->leave->leave_type,
            'start_date' => $this->leave->start_date->format('Y-m-d'),
            'end_date' => $this->leave->end_date->format('Y-m-d'),
            'total_days' => $this->leave->total_days,
            'current_level' => $this->leave->current_approval_level,
            'approval_status' => $this->leave->approval_status,
            'type' => 'leave_pending',
            'message' => 'Leave request pending approval from ' . $this->leave->employee_name,
        ];
    }
}