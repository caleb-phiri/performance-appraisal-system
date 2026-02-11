<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Leave Application #{{ $leave->id }}</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
    --moic-navy:#110484;
    --moic-orange:#e7581c;
}

/* Background Gradient */
body{
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}

/* Container */
.wrapper{
    max-width:1000px;
    margin:auto;
}

/* Main Card */
.card{
    background:white;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
    overflow:hidden;
}

/* Gradient Header */
.header-gradient{
    background:linear-gradient(120deg,#110484,#1a25b3);
    color:white;
}

/* Section Styling */
.section{
    padding:24px;
}

.section-title{
    font-weight:700;
    color:var(--moic-navy);
    margin-bottom:16px;
    font-size:18px;
}

/* Label + Values */
.label{
    font-size:12px;
    color:#6b7280;
}

.value{
    font-weight:600;
    color:#111827;
}

/* Info Cards */
.info-card{
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:10px;
    padding:14px;
    transition:.2s;
}

.info-card:hover{
    background:#f3f4f6;
}

/* Status Badge */
.status{
    padding:7px 16px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

/* Action Reason Boxes */
.reason-box{
    margin-top:12px;
    padding:16px;
    border-radius:10px;
}

.approved{
    background:#ecfdf5;
    border-left:6px solid #22c55e;
}

.rejected{
    background:#fef2f2;
    border-left:6px solid #ef4444;
}

.cancelled{
    background:#fff7ed;
    border-left:6px solid #f97316;
}

/* Buttons */
.btn{
    padding:10px 18px;
    border-radius:8px;
    font-weight:600;
    transition:.2s;
}

.btn-primary{
    background:#110484;
    color:white;
}

.btn-primary:hover{
    background:#0d0366;
}

.btn-success{
    background:#16a34a;
    color:white;
}

.btn-success:hover{
    background:#15803d;
}

.btn-danger{
    background:#dc2626;
    color:white;
}

.btn-danger:hover{
    background:#b91c1c;
}

/* Mobile */
@media(max-width:768px){
    .grid{
        grid-template-columns:1fr !important;
    }
}

</style>
</head>

<body class="min-h-screen">

@include('layouts.navigation')

<div class="wrapper px-4 py-10">

<!-- PAGE HEADER -->
<div class="flex justify-between items-center mb-6">

<div>
<h1 class="text-3xl font-bold text-[#110484]">
Leave Application
</h1>

<p class="text-gray-500">
Application ID: {{ $leave->id }}
</p>
</div>

<a href="{{ route('leave.index') }}"
class="font-semibold text-[#110484] hover:text-[#e7581c]">
<i class="fas fa-arrow-left mr-1"></i> Back
</a>

</div>



<!-- MAIN CARD -->
<div class="card">

<!-- TOP SUMMARY -->
<div class="header-gradient section flex justify-between items-center">

<div>
<h2 class="text-xl font-bold">
{{ $leave->leave_type_name }}
</h2>

<p class="text-blue-100 text-sm mt-1">
{{ $leave->start_date->format('M d, Y') }}
—
{{ $leave->end_date->format('M d, Y') }}
</p>
</div>

<span class="status {{ $leave->status_badge }}">
{{ strtoupper($leave->status) }}
</span>

</div>



<!-- EMPLOYEE -->
<div class="section border-b">

<div class="section-title">
<i class="fas fa-user mr-2"></i>
Employee Information
</div>

<div class="grid md:grid-cols-2 gap-4">

<div class="info-card">
<p class="label">Employee</p>
<p class="value">{{ $leave->employee_name }}</p>
</div>

<div class="info-card">
<p class="label">Employee Number</p>
<p class="value">{{ $leave->employee_number }}</p>
</div>

<div class="info-card">
<p class="label">Department</p>
<p class="value">{{ $leave->department }}</p>
</div>

<div class="info-card">
<p class="label">Job Title</p>
<p class="value">{{ $leave->job_title }}</p>
</div>

</div>
</div>



<!-- LEAVE DETAILS -->
<div class="section border-b">

<div class="section-title">
<i class="fas fa-calendar-check mr-2"></i>
Leave Details
</div>

<div class="grid md:grid-cols-3 gap-4">

<div class="info-card">
<p class="label">Leave Type</p>
<p class="value">{{ $leave->leave_type_name }}</p>
</div>

<div class="info-card">
<p class="label">Total Days</p>
<p class="value">{{ $leave->total_days }}</p>
</div>

<div class="info-card">
<p class="label">Applied On</p>
<p class="value">
{{ $leave->created_at->format('M d, Y') }}
</p>
</div>

</div>
</div>



<!-- REASON -->
<div class="section border-b">

<div class="section-title">
<i class="fas fa-comment-dots mr-2"></i>
Reason for Leave
</div>

<div class="bg-indigo-50 border border-indigo-100 p-4 rounded-lg text-gray-700">
{{ $leave->reason }}
</div>

</div>



<!-- CONTACT -->
@if($leave->contact_address || $leave->contact_phone)

<div class="section border-b">

<div class="section-title">
<i class="fas fa-phone mr-2"></i>
Contact During Leave
</div>

<div class="grid md:grid-cols-2 gap-4">

@if($leave->contact_address)
<div class="info-card">
<p class="label">Address</p>
<p class="value">{{ $leave->contact_address }}</p>
</div>
@endif

@if($leave->contact_phone)
<div class="info-card">
<p class="label">Phone</p>
<p class="value">{{ $leave->contact_phone }}</p>
</div>
@endif

</div>
</div>

@endif



<!-- ACTION TAKEN -->
@if(in_array($leave->status,['approved','rejected','cancelled']))

<div class="section">

<div class="section-title">
<i class="fas fa-user-check mr-2"></i>
Offical remarks 
</div>

<div class="grid md:grid-cols-3 gap-4">

<div class="info-card">
<p class="label">Action By</p>
<p class="value">
{{ $leave->approved_by ?? 'System' }}
</p>
</div>

<div class="info-card">
<p class="label">Action Date</p>
<p class="value">
{{ optional($leave->approved_at)->format('M d, Y h:i A') ?? 'N/A' }}
</p>
</div>

<div class="info-card">
<p class="label">Final Status</p>
<p class="value">
{{ ucfirst($leave->status) }}
</p>
</div>

</div>

@if($leave->remarks)

<div class="reason-box
@if($leave->status=='approved') approved
@elseif($leave->status=='rejected') rejected
@else cancelled
@endif">

<strong>
Reason for {{ ucfirst($leave->status) }}:
</strong>

<p class="mt-2">
{{ $leave->remarks }}
</p>

</div>

@endif

</div>

@endif



<!-- FOOTER -->
<div class="section border-t bg-gray-50 flex justify-end gap-3">

<a href="{{ route('leave.index') }}"
class="btn border">
Back
</a>

@if($leave->isPending())

<a href="{{ route('leave.edit',$leave->id) }}"
class="btn btn-success">
Edit
</a>

<form action="{{ route('leave.destroy',$leave->id) }}"
method="POST">
@csrf
@method('DELETE')

<button
class="btn btn-danger"
onclick="return confirm('Cancel this leave request?')">
Cancel
</button>

</form>

@endif

</div>

</div>
</div>
</body>
</html>
