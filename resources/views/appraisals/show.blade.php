<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Appraisal Report - MOIC Performance Appraisal System</title>
    <!-- Signature Pad Library -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
   <!-- FAVICON - Using TK.png -->
  <link rel="icon" type="image/png" href="{{ asset('images/TK.png') }}">
  <link rel="shortcut icon" href="{{ asset('images/TK.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/TK.png') }}">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Fonts & icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Apple Touch Icon (for iOS home screen) -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- html2pdf for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <!-- Meta CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* MOIC Brand Colors */
        :root {
            --moic-navy: #110484;
            --moic-navy-light: #3328a5;
            --moic-accent: #e7581c;
            --moic-accent-light: #ff6b2d;
            --moic-blue: #1a0c9e;
            --moic-blue-light: #2d1fd1;
            --moic-gradient: linear-gradient(135deg, var(--moic-navy), var(--moic-blue));
            --moic-gradient-accent: linear-gradient(135deg, var(--moic-accent), #ff7c45);
            --moic-gradient-mixed: linear-gradient(135deg, var(--moic-navy), var(--moic-accent));
            
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
        }
        
        /* Base styles */
        html {
            font-size: 16px;
        }
        
        body {
            font-size: 0.875rem;
            line-height: 1.5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
        }
        
        /* Custom Color Classes */
        .moic-navy { color: var(--moic-navy) !important; }
        .moic-navy-bg { background-color: var(--moic-navy) !important; }
        .moic-accent { color: var(--moic-accent) !important; }
        .moic-accent-bg { background-color: var(--moic-accent) !important; }
        
        /* MOIC Buttons */
        .btn-moic {
            background: var(--moic-gradient);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-moic:hover {
            background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-blue-light));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .btn-accent {
            background: var(--moic-gradient-accent);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, var(--moic-accent-light), #ff8d5c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.3);
        }
        
        .btn-outline-moic {
            background: transparent;
            border: 1px solid var(--moic-navy);
            color: var(--moic-navy) !important;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-outline-moic:hover {
            background: var(--moic-navy);
            color: white !important;
            transform: translateY(-1px);
        }
        
        /* PIP Button Styles */
        .btn-pip {
            background: linear-gradient(135deg, #e7581c, #c2410c);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .btn-pip:hover {
            background: linear-gradient(135deg, #c2410c, #9a3412);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.3);
        }
        
        .btn-pip-outline {
            background: transparent;
            border: 2px solid #e7581c;
            color: #e7581c !important;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .btn-pip-outline:hover {
            background: #e7581c;
            color: white !important;
            transform: translateY(-1px);
        }
        
        /* Animated Gradient Header */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
            box-shadow: 0 2px 10px rgba(17, 4, 132, 0.15);
        }
        
        /* Logo Container */
        .logo-container {
            position: relative;
            padding: 2px;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .logo-inner {
            background: white;
            border-radius: 0.375rem;
            padding: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.125rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        /* Card Styling */
        .card-moic {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card-moic:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Stat Cards */
        .stat-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            padding: 1rem;
            height: 100%;
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .stat-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* Table Styling - Enhanced Responsive */
        .table-moic {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-moic thead th {
            background: var(--moic-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.75rem 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            vertical-align: middle;
            white-space: nowrap;
        }
        
        .table-moic tbody td {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
            border-color: #f3f4f6;
        }
        
        .table-moic tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table-moic tbody tr:hover {
            background-color: #f9fafb;
        }
        
        /* Responsive Table Card View for Mobile */
        @media (max-width: 768px) {
            .table-moic,
            .table-moic thead,
            .table-moic tbody,
            .table-moic th,
            .table-moic td,
            .table-moic tr {
                display: block;
            }
            
            .table-moic thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            
            .table-moic tbody tr {
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                padding: 0.75rem;
                background: white;
            }
            
            .table-moic tbody td {
                border: none;
                border-bottom: 1px solid #f3f4f6;
                position: relative;
                padding-left: 40%;
                text-align: left;
                white-space: normal;
                min-height: 2.5rem;
            }
            
            .table-moic tbody td:before {
                position: absolute;
                top: 0.75rem;
                left: 0.75rem;
                width: 35%;
                padding-right: 0.625rem;
                white-space: nowrap;
                font-weight: 600;
                content: attr(data-label);
                color: var(--moic-navy);
            }
            
            .table-moic tbody td:last-child {
                border-bottom: 0;
            }
            
            .kpa-details {
                max-width: 100%;
            }
            
            .kpa-indicators {
                max-height: none;
            }
        }
        
        /* Status Badges */
        .badge-draft {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-submitted {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-approved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-rejected {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-in-review {
            background-color: #f3e8ff !important;
            color: #7c3aed !important;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-archived {
            background-color: #f3f4f6 !important;
            color: #6b7280 !important;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-pip {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-pip-active {
            background-color: #ffedd5 !important;
            color: #9a3412 !important;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Score Colors */
        .score-excellent { color: #059669 !important; font-weight: 600; }
        .score-good { color: #2563eb !important; font-weight: 600; }
        .score-fair { color: #d97706 !important; font-weight: 600; }
        .score-poor { color: #dc2626 !important; font-weight: 600; }
        
        /* Progress Bars */
        .progress-moic {
            height: 0.5rem;
            border-radius: 0.25rem;
            background-color: #e5e7eb;
            width: 6rem;
        }
        
        .progress-bar-moic {
            background: var(--moic-gradient);
            border-radius: 0.25rem;
        }
        
        /* Action Buttons */
        .action-btn {
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Comment Card Styles */
        .comment-card {
            background: #f8fafc;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .comment-card:hover {
            background: #f1f5f9;
            transform: translateX(2px);
        }
        
        .comment-card.employee-comment {
            border-left-color: var(--moic-navy);
        }
        
        .comment-card.supervisor-comment {
            border-left-color: var(--moic-accent);
        }
        
        .comment-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            gap: 0.5rem;
        }
        
        .comment-author {
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .comment-date {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .comment-preview {
            font-size: 0.875rem;
            color: #334155;
            margin-bottom: 0.5rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .comment-expand-btn {
            font-size: 0.75rem;
            color: var(--moic-navy);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        
        .comment-expand-btn:hover {
            text-decoration: underline;
        }
        
        /* KPA Details Styling - Responsive */
        .kpa-details {
            max-width: 350px;
        }
        
        @media (max-width: 768px) {
            .kpa-details {
                max-width: 100%;
            }
        }
        
        .kpa-title {
            font-weight: 600;
            color: var(--moic-navy);
            margin-bottom: 0.25rem;
            word-break: break-word;
        }
        
        .kpa-description {
            font-size: 0.8rem;
            color: #4b5563;
            margin-bottom: 0.25rem;
            word-break: break-word;
        }
        
        .kpa-indicators {
            font-size: 0.75rem;
            background-color: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            color: #1f2937;
            word-break: break-word;
            max-height: 60px;
            overflow-y: auto;
            border-left: 2px solid var(--moic-accent);
        }
        
        .kpa-indicators:hover {
            background-color: #e5e7eb;
        }
        
        @media (max-width: 768px) {
            .kpa-indicators {
                max-height: none;
            }
        }
        
        .kpa-badge {
            font-size: 0.65rem;
            background-color: #e9e7ff;
            color: var(--moic-navy);
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            margin-right: 0.25rem;
        }
        
        .kpa-metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.25rem;
            font-size: 0.7rem;
        }
        
        .kpa-target {
            background-color: #fee7e0;
            color: var(--moic-accent);
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
        }
        
        .kpa-actual {
            background-color: #e0e7ff;
            color: var(--moic-navy);
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
        }
        
        /* Message Container */
        .message-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1050;
            max-width: 90%;
            width: 400px;
        }
        
        @media (max-width: 576px) {
            .message-container {
                width: calc(100% - 2rem);
                left: 1rem;
                right: 1rem;
                max-width: none;
            }
        }
        
        .message {
            margin-bottom: 0.5rem;
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.3s ease-out;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }
        
        .message-success { background: linear-gradient(135deg, #10b981, #059669); }
        .message-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .message-info { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .message-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        
        .message-close {
            background: none;
            border: none;
            color: white;
            opacity: 0.8;
            cursor: pointer;
            padding: 0;
            margin-left: 0.75rem;
        }
        
        .message-close:hover {
            opacity: 1;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        /* Modal Styling - Responsive */
        .modal-moic .modal-header {
            background: var(--moic-gradient);
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        
        .modal-moic .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
        }
        
        /* Comment Modal Specific */
        .comment-full-content {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-size: 1rem;
            line-height: 1.6;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            max-height: 400px;
            overflow-y: auto;
        }
        
        @media (max-width: 576px) {
            .comment-full-content {
                max-height: 300px;
            }
        }
        
        .comment-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .comment-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #475569;
        }
        
        .rating-option {
            display: inline-block;
            width: 3rem;
            height: 3rem;
            line-height: 3rem;
            text-align: center;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .rating-option:hover {
            border-color: var(--moic-navy);
            background-color: rgba(17, 4, 132, 0.05);
        }
        
        .rating-option.selected {
            background: var(--moic-gradient);
            color: white;
            border-color: var(--moic-navy);
        }
        
        @media (max-width: 576px) {
            .rating-option {
                width: 2.5rem;
                height: 2.5rem;
                line-height: 2.5rem;
            }
        }
        
        /* Background utilities */
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-indigo-50 { background-color: #eef2ff !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-emerald-50 { background-color: #ecfdf5 !important; }
        .bg-yellow-50 { background-color: #fefce8 !important; }
        .bg-amber-50 { background-color: #fffbeb !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .bg-purple-50 { background-color: #faf5ff !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        .bg-pink-50 { background-color: #fdf2f8 !important; }
        .bg-teal-50 { background-color: #f0fdfa !important; }
        
        /* Text utilities */
        .text-green-800 { color: #166534 !important; }
        .text-purple-800 { color: #6b21a8 !important; }
        .text-blue-800 { color: #1e40af !important; }
        .text-yellow-800 { color: #92400e !important; }
        .text-red-800 { color: #991b1b !important; }
        
        /* Responsive container */
        .container-custom {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (max-width: 640px) {
            .container-custom {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
        
        /* Supervisor rating badge */
        .supervisor-rating-badge {
            font-size: 0.7rem;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
        }
        
        /* Truncate tooltip */
        .truncate-tooltip {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 120px;
            cursor: help;
        }
        
        /* Responsive Flex Utilities */
        .flex-responsive {
            display: flex;
            flex-wrap: wrap;
        }
        
        .gap-responsive {
            gap: 1rem;
        }
        
        @media (max-width: 640px) {
            .gap-responsive {
                gap: 0.75rem;
            }
        }
        
        /* Responsive Text Sizes */
        @media (max-width: 768px) {
            .h3 {
                font-size: 1.25rem;
            }
            
            .h5 {
                font-size: 1rem;
            }
            
            .stat-number {
                font-size: 1.25rem;
            }
        }
        
        /* Responsive Stats Cards */
        @media (max-width: 768px) {
            .stat-icon {
                width: 2.5rem;
                height: 2.5rem;
            }
            
            .stat-icon i {
                font-size: 1rem;
            }
            
            .stat-card {
                padding: 0.75rem;
            }
        }
        
        /* Responsive Button Groups */
        .btn-group-responsive {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        @media (max-width: 480px) {
            .btn-group-responsive .btn {
                width: 100%;
            }
        }
        
        /* Responsive Navigation */
        @media (max-width: 640px) {
            .nav-links {
                flex-direction: column;
                width: 100%;
            }
            
            .nav-links .btn {
                width: 100%;
                text-align: center;
            }
        }
        
        /* Responsive Footer */
        @media (max-width: 768px) {
            footer .row {
                flex-direction: column;
                text-align: center;
            }
            
            footer .d-flex {
                justify-content: center;
            }
            
            .justify-content-lg-end {
                justify-content: center !important;
            }
        }
        
        /* Responsive Status Banner */
        @media (max-width: 768px) {
            .status-banner {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .status-actions {
                width: 100%;
            }
            
            .status-actions .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .status-actions .btn:last-child {
                margin-bottom: 0;
            }
        }
        
        /* Responsive Multiple Supervisors Grid */
        @media (max-width: 768px) {
            .supervisors-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .supervisors-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Responsive Comment Cards */
        @media (max-width: 768px) {
            .comment-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        
        /* Responsive Progress Bars */
        @media (max-width: 768px) {
            .progress-moic {
                width: 4rem;
            }
        }
        
        /* Responsive Table Foot */
        @media (max-width: 768px) {
            tfoot tr {
                display: flex;
                flex-direction: column;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 0.75rem;
                background: #f9fafb;
            }
            
            tfoot td {
                display: flex;
                justify-content: space-between;
                border: none;
                padding: 0.5rem 0;
            }
            
            tfoot td:not(:last-child) {
                border-bottom: 1px solid #e5e7eb;
            }
        }
        
        /* Responsive Modals */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-footer {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .modal-footer .btn {
                width: 100%;
            }
        }
        
        /* Touch-friendly buttons on mobile */
        @media (max-width: 768px) {
            .btn {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
            
            .btn-sm {
                padding: 0.4rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .action-btn {
                width: 2.5rem;
                height: 2.5rem;
            }
        }
        
        /* Hide calculation formulas from users */
        .score-formula {
            display: none !important;
        }
        
        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
            
            .gradient-header {
                animation: none;
            }
            
            .stat-card:hover,
            .card-moic:hover {
                transform: none;
            }
        }
        
        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .gradient-header {
                background: #110484 !important;
                animation: none !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .table-moic thead th {
                background: #110484 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color: white !important;
            }
            
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .card-moic {
                box-shadow: none;
                border: 1px solid #ccc;
                break-inside: avoid;
                page-break-inside: avoid;
            }
            
            .container-custom {
                max-width: 100%;
                padding: 0;
            }
            
            .progress-bar {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .badge, .status-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Ensure tables print properly */
            .table-moic {
                break-inside: avoid;
            }
            
            .table-moic tbody tr {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
        
        /* --- Report-specific styling enhancements --- */
        .performance-rating-badge {
            display: inline-block;
            background-color: #f0fdf4;
            color: #166534;
            border-radius: 2rem;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .summary-stat-block {
            background-color: #f8fafc;
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .summary-stat-block:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .summary-stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin-bottom: 0.25rem;
        }
        
        .summary-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .kpi-section-title {
            border-left: 4px solid var(--moic-accent);
            padding-left: 0.75rem;
        }
        
        .comment-section-card {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        
        .comment-section-card:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        /* Prevent focus issues with hidden modals */
        .modal:not(.show) {
            display: none !important;
            visibility: hidden !important;
        }
        
        .modal.show {
            display: block !important;
            visibility: visible !important;
        }
        
        /* Ensure modal content is accessible when visible */
        .modal.show .modal-content {
            pointer-events: auto;
        }
        
        /* Download button loading state */
        .btn-download-loading {
            opacity: 0.7;
            cursor: wait;
        }
        
        /* Report container for PDF */
        #reportContainer {
            background: white;
        }
        
        /* PIP Banner Styles */
        .pip-warning-banner {
            background: linear-gradient(135deg, #fef3c7, #ffedd5);
            border-left: 5px solid #e7581c;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .pip-active-banner {
            background: linear-gradient(135deg, #ffedd5, #fef3c7);
            border-left: 5px solid #9a3412;
        }

        /* New PIP Details Card Styles - Clean, modern, no <br> tags */
        .pip-detail-card {
            border-radius: 20px;
            background: #ffffff;
            border: 1px solid #f0e2d6;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .pip-detail-header {
            background: #fffaf5;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3e9e0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .pip-detail-header i {
            font-size: 1.4rem;
            color: #b45309;
        }
        .pip-detail-header .title {
            font-weight: 700;
            color: #4b2e1a;
            font-size: 1.1rem;
            margin: 0;
        }
        .pip-detail-body {
            padding: 1.5rem;
            background: #ffffff;
        }
        .pip-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }
        .pip-meta-item {
            background: #fefaf5;
            border-radius: 16px;
            padding: 0.6rem 1.2rem;
            flex: 1 1 180px;
            border: 1px solid #f3e7de;
        }
        .pip-meta-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #9b6a4b;
            margin-bottom: 0.25rem;
            display: block;
        }
        .pip-meta-value {
            font-weight: 600;
            color: #2c1c10;
            font-size: 0.95rem;
        }
        .pip-content-box {
            background: #fefcf8;
            border-radius: 20px;
            padding: 1.25rem;
            border: 1px solid #f5eee7;
            line-height: 1.55;
            color: #2e241e;
            font-size: 0.95rem;
        }
        .pip-content-box p:last-child {
            margin-bottom: 0;
        }
        .pip-badge-active {
            background: #ffedd5;
            color: #9a3412;
            border-radius: 30px;
            padding: 0.2rem 0.8rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .pip-footer-meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f0e2d6;
            font-size: 0.75rem;
            color: #9b7a62;
        }
        /* 90-day PIP Action Table */
        .pip-action-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .pip-action-table th,
        .pip-action-table td {
            border: 1px solid #f0e2d6;
            padding: 0.75rem;
            vertical-align: top;
        }
        .pip-action-table th {
            background: #fefaf5;
            font-weight: 700;
            color: #4b2e1a;
        }
        /* Follow-up comments section */
        .followup-section {
            background: #fefcf8;
            border-radius: 1rem;
            border: 1px solid #f0e2d6;
            padding: 1.25rem;
            margin-top: 1rem;
        }
        .btn-submit-followup {
            background: linear-gradient(135deg, #e7581c, #c2410c);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-submit-followup:hover {
            background: #c2410c;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(231,88,28,0.3);
        }

        /* PIP Signature Styles */
.pip-signature-card {
    border-radius: 20px;
    background: #ffffff;
    border: 1px solid #f0e2d6;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
}
.pip-signature-header {
    background: #fffaf5;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f3e9e0;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: space-between;
}
.pip-signature-header i {
    font-size: 1.4rem;
    color: #b45309;
}
.pip-signature-header .title {
    font-weight: 700;
    color: #4b2e1a;
    font-size: 1.1rem;
    margin: 0;
}
.pip-signature-body {
    padding: 1.5rem;
    background: #ffffff;
}
.signature-canvas {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    background: white;
    cursor: crosshair;
    width: 100%;
    height: 150px;
    touch-action: none;
}
.signature-pad-container {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
}
.signature-status {
    font-size: 0.7rem;
    padding: 0.25rem 0.75rem;
    border-radius: 2rem;
}
.signature-status-signed {
    background: #d1fae5;
    color: #065f46;
}
.signature-status-pending {
    background: #fee2e2;
    color: #991b1b;
}
.btn-submit-signature {
    background: linear-gradient(135deg, #e7581c, #c2410c);
    color: white;
    border: none;
    padding: 0.5rem 1.5rem;
    border-radius: 2rem;
    font-weight: 600;
    transition: all 0.2s;
}
.btn-submit-signature:hover {
    background: #c2410c;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(231,88,28,0.3);
}
    </style>
</head>
<body>
    @php
    // Helper function to safely get supervisor name
    if (!function_exists('getSupervisorName')) {
        function getSupervisorName($supervisor) {
            if (is_object($supervisor)) {
                if (property_exists($supervisor, 'name') && $supervisor->name) {
                    return $supervisor->name;
                }
                if (property_exists($supervisor, 'full_name') && $supervisor->full_name) {
                    return $supervisor->full_name;
                }
                if (property_exists($supervisor, 'employee_name') && $supervisor->employee_name) {
                    return $supervisor->employee_name;
                }
                if (property_exists($supervisor, 'first_name') && property_exists($supervisor, 'last_name')) {
                    return $supervisor->first_name . ' ' . $supervisor->last_name;
                }
                if (isset($supervisor->pivot) && isset($supervisor->pivot->supervisor_id)) {
                    return 'Supervisor ' . $supervisor->pivot->supervisor_id;
                }
                if (method_exists($supervisor, 'getFullNameAttribute')) {
                    return $supervisor->getFullNameAttribute();
                }
            }
            if (is_array($supervisor)) {
                return $supervisor['name'] ?? $supervisor['full_name'] ?? $supervisor['employee_name'] ?? 'Supervisor';
            }
            return 'Supervisor';
        }
    }
    
    // Calculate scores for PIP threshold
    $totalWeight = $appraisal->kpas->sum('weight');
    $totalScore = 0;
    foreach($appraisal->kpas as $kpa) {
        $kpi = $kpa->kpi ?: 4;
        $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
        $totalScore += ($finalRating / $kpi) * $kpa->weight;
    }
    
    $requiresPIP = $totalScore < 75 && !($appraisal->pip_initiated ?? false);
    $pipActive = $appraisal->pip_initiated ?? false;
    $isSupervisor = auth()->user()->user_type === 'supervisor';
    
    // PIP Signature statuses
    $pipEmployeeSigned = $appraisal->pip_employee_signed ?? false;
    $pipSupervisorSigned = $appraisal->pip_supervisor_signed ?? false;
    $pipEmployeeSignedAt = $appraisal->pip_employee_signed_at ?? null;
    $pipSupervisorSignedAt = $appraisal->pip_supervisor_signed_at ?? null;
    
    // ========== ADD THIS BLOCK ==========
    // Action Plan Data for PIP Details Modal
    $actionPlanData = null;
    if (!empty($appraisal->pip_action_plan)) {
        if (is_string($appraisal->pip_action_plan)) {
            $actionPlanData = json_decode($appraisal->pip_action_plan, true);
        } else {
            $actionPlanData = $appraisal->pip_action_plan;
        }
    }
    
    $actionPlan = (!empty($actionPlanData) && is_array($actionPlanData) && count($actionPlanData) > 0) 
        ? $actionPlanData 
        : [
            ['action' => 'Submit weekly progress report every Friday', 'objective' => 'Track improvements and identify blockers', 'measurement' => 'Completion rate / quality audit'],
            ['action' => 'Complete mandatory e-learning modules', 'objective' => 'Skill enhancement and process knowledge', 'measurement' => 'Certification achieved'],
            ['action' => 'Achieve 90% quality score on monthly audits', 'objective' => 'Quality and compliance improvement', 'measurement' => 'Dashboard review / audit results']
        ];
    // ========== END ADDED BLOCK ==========
    
    // Prepare JSON-encoded content for modals
    $developmentNeedsJson = json_encode($appraisal->development_needs ?? '');
    $employeeCommentsJson = json_encode($appraisal->employee_comments ?? '');
    $supervisorCommentsJson = json_encode($appraisal->supervisor_comments ?? '');
    
    // Get current date for report
    $reportDate = now()->format('F d, Y');
@endphp

    <!-- Message Container -->
    <div id="messageContainer" class="message-container"></div>

    <!-- Main Report Container (for PDF generation) -->
    <div id="reportContainer">
        <!-- Header with Animated Gradient -->
        <div class="gradient-header text-white no-print">
            <div class="container-custom px-3 py-2">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <!-- Logo Section -->
                    <div class="d-flex align-items-center flex-wrap">
                        <!-- Dual Logo Container -->
                        <div class="logo-container me-2 me-sm-3">
                            <div class="logo-inner">
                                <div class="d-flex align-items-center gap-1 gap-sm-2">
                                    <!-- MOIC Logo -->
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-white rounded p-1 mb-1">
                                            <img class="img-fluid" style="height: 1.25rem; height: clamp(1rem, 4vw, 1.5rem);" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                                        </div>
                                        <span class="status-badge moic-navy-bg text-white">MOIC</span>
                                    </div>
                                    
                                    <!-- Partnership Badge -->
                                    <div class="position-relative">
                                        <div class="rounded-circle bg-gradient-to-br from-[#110484] to-[#e7581c]" style="width: 1.5rem; height: 1.5rem; width: clamp(1.25rem, 5vw, 2rem); height: clamp(1.25rem, 5vw, 2rem); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-handshake text-white" style="font-size: 0.6rem; font-size: clamp(0.5rem, 2vw, 0.75rem);"></i>
                                        </div>
                                        <div class="position-absolute top-100 start-50 translate-middle mt-1">
                                            <span class="status-badge bg-white moic-navy">PARTNERS</span>
                                        </div>
                                    </div>
                                    
                                    <!-- TKC Logo -->
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-white rounded p-1 mb-1">
                                            <img class="img-fluid" style="height: 1.25rem; height: clamp(1rem, 4vw, 1.5rem);" src="{{ asset('images/TKC.png') }}" alt="TKC Logo">
                                        </div>
                                        <span class="status-badge moic-accent-bg text-white">TKC</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Page Title - Desktop -->
                        <div class="d-none d-md-flex align-items-center">
                            <div class="vr bg-white opacity-25 mx-2 mx-lg-3" style="height: 1.5rem;"></div>
                            <div>
                                <h1 class="h6 h5-md mb-0 fw-bold">Performance Appraisal Report</h1>
                                <p class="mb-0 text-white-50 small">ID: #{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                        
                        <!-- Page Title - Mobile -->
                        <div class="d-md-none ms-2">
                            <h1 class="h6 mb-0 fw-bold">Report</h1>
                            <p class="mb-0 text-white-50 small">#{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <!-- User Info & Actions -->
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-none d-md-block text-end me-2">
                            <div class="fw-medium small">{{ auth()->user()->name ?? 'User' }}</div>
                            <div class="small text-white-50">{{ auth()->user()->user_type ?? 'Employee' }}</div>
                        </div>
                        <div class="avatar avatar-gradient bg-white text-moic-navy rounded-circle d-flex align-items-center justify-content-center" style="width: 2rem; height: 2rem; min-width: 2rem;">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <div class="bg-white border-bottom shadow-sm no-print">
            <div class="container-custom px-3 py-2">
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-wrap gap-2 nav-links">
                        <a href="{{ route('appraisals.index') }}" class="btn btn-outline-moic btn-sm">
                            <i class="fas fa-list me-2"></i><span class="d-none d-sm-inline">All Appraisals</span><span class="d-sm-none">List</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-moic btn-sm">
                            <i class="fas fa-home me-2"></i><span class="d-none d-sm-inline">Dashboard</span><span class="d-sm-none">Home</span>
                        </a>
                        @if(auth()->user()->user_type === 'supervisor')
                        <a href="{{ route('supervisor.dashboard') }}" class="btn btn-accent btn-sm">
                            <i class="fas fa-chart-line me-2"></i><span class="d-none d-sm-inline">Supervisor Panel</span><span class="d-sm-none">Panel</span>
                        </a>
                        @endif
                    </div>
                    <li class="nav-item">
    <a class="nav-link" href="{{ route('pip.management') }}">
        <i class="fas fa-chart-line me-2"></i>
        <span>PIP Management</span>
        @php
            $activePipCount = App\Models\Appraisal::where('pip_initiated', true)
                ->where('pip_end_date', '>=', now())
                ->count();
        @endphp
        @if($activePipCount > 0)
            <span class="badge bg-danger ms-2">{{ $activePipCount }}</span>
        @endif
    </a>
</li>
                    <!-- Appraisal Meta Info -->
                    <div class="text-muted small">
                        <i class="fas fa-calendar-alt me-1 moic-accent"></i>
                        <span class="d-none d-sm-inline">{{ $appraisal->period }}</span>
                        <span class="d-sm-none">{{ \Carbon\Carbon::parse($appraisal->start_date)->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="bg-white border-bottom no-print">
            <div class="container-custom px-3 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="d-flex flex-wrap align-items-center gap-2 gap-md-3">
                        @php
                            $statusColors = [
                                'draft' => ['badge' => 'badge-draft', 'icon' => 'fa-edit'],
                                'submitted' => ['badge' => 'badge-submitted', 'icon' => 'fa-paper-plane'],
                                'in_review' => ['badge' => 'badge-in-review', 'icon' => 'fa-search'],
                                'approved' => ['badge' => 'badge-approved', 'icon' => 'fa-check-circle'],
                                'rejected' => ['badge' => 'badge-rejected', 'icon' => 'fa-times-circle'],
                                'completed' => ['badge' => 'badge-approved', 'icon' => 'fa-check-double'],
                                'archived' => ['badge' => 'badge-archived', 'icon' => 'fa-archive'],
                            ];
                            $statusInfo = $statusColors[$appraisal->status] ?? ['badge' => 'badge-pending', 'icon' => 'fa-clock'];
                        @endphp
                        
                        <span class="badge {{ $statusInfo['badge'] }} px-2 px-md-3 py-2">
                            <i class="fas {{ $statusInfo['icon'] }} me-1 me-md-2"></i>
                            <span class="d-none d-sm-inline">{{ str_replace('_', ' ', strtoupper($appraisal->status)) }}</span>
                            <span class="d-sm-none">{{ substr(strtoupper(str_replace('_', ' ', $appraisal->status)), 0, 3) }}</span>
                        </span>
                        
                        @if($pipActive)
                        <span class="badge badge-pip-active px-2 px-md-3 py-2">
                            <i class="fas fa-chart-line me-1 me-md-2"></i>
                            <span>PIP Active</span>
                        </span>
                        @endif
                        
                        @if($appraisal->approved_at)
                        <span class="text-muted small">
                            <i class="fas fa-calendar-check me-1 text-success"></i>
                            <span class="d-none d-md-inline">Approved {{ \Carbon\Carbon::parse($appraisal->approved_at)->format('M d, Y') }}</span>
                            <span class="d-md-none">{{ \Carbon\Carbon::parse($appraisal->approved_at)->format('m/d/y') }}</span>
                        </span>
                        @endif
                        
                        <span class="text-muted small">
                            <i class="fas fa-user me-1 moic-navy"></i>
                            <span class="d-none d-md-inline">{{ $appraisal->user->name ?? $appraisal->employee_name }}</span>
                            <span class="d-md-none">{{ Str::limit($appraisal->user->name ?? $appraisal->employee_name, 8) }}</span>
                        </span>
                        
                        <span class="text-muted small">
                            <i class="fas fa-id-badge me-1 moic-accent"></i>
                            <span class="d-none d-md-inline">{{ $appraisal->employee_number }}</span>
                            <span class="d-md-none">{{ substr($appraisal->employee_number, -4) }}</span>
                        </span>
                    </div>
                    
                    <!-- Download Report Button -->
                    <div class="d-flex flex-wrap gap-2">
                        <button onclick="downloadReport()" class="btn btn-accent btn-sm" id="downloadReportBtn">
                            <i class="fas fa-download me-2"></i>Download Report (PDF)
                        </button>
                    </div>
                    
                    <!-- Status Actions for Supervisor -->
                    @php
                        $currentUser = auth()->user();
                        $isAssignedSupervisor = false;
                        $isPrimarySupervisor = false;
                        
                        if ($currentUser->user_type === 'supervisor') {
                            $employee = $appraisal->user;
                            if ($employee && $employee->ratingSupervisors) {
                                foreach ($employee->ratingSupervisors as $supervisor) {
                                    $supervisorId = $supervisor->employee_number ?? 
                                                   ($supervisor->id ?? 
                                                   ($supervisor->pivot->supervisor_id ?? null));
                                    if ($supervisorId === $currentUser->employee_number) {
                                        $isAssignedSupervisor = true;
                                        $isPrimarySupervisor = $supervisor->pivot->is_primary ?? false;
                                        break;
                                    }
                                }
                            }
                            // Fallback for old system (manager_id)
                            if (!$isAssignedSupervisor && $employee && $employee->manager_id === $currentUser->employee_number) {
                                $isAssignedSupervisor = true;
                                $isPrimarySupervisor = true;
                            }
                        }
                        
                        $canApprove = $isPrimarySupervisor || $currentUser->user_type === 'hr' || $currentUser->user_type === 'admin';
                    @endphp
                    
                    @if($isAssignedSupervisor && $canApprove)
                    <div class="d-flex flex-wrap gap-2 w-100 w-md-auto status-actions">
                        @if($appraisal->status === 'submitted' || $appraisal->status === 'in_review')
                            <button onclick="showRatingModal('{{ $appraisal->employee_number }}')" 
                                    class="btn btn-warning btn-sm flex-fill flex-md-grow-0">
                                <i class="fas fa-star me-2"></i><span class="d-none d-sm-inline">Quick Rate</span><span class="d-sm-none">Rate</span>
                            </button>
                            
                            <button onclick="showReturnModal()" 
                                    class="btn btn-sm flex-fill flex-md-grow-0" style="background: #f97316; color: white;">
                                <i class="fas fa-undo me-2"></i><span class="d-none d-sm-inline">Return</span><span class="d-sm-none">Revise</span>
                            </button>
                            
                            <form action="{{ route('appraisals.approve', $appraisal->id) }}" method="POST" class="d-inline flex-fill flex-md-grow-0">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-success btn-sm w-100"
                                        onclick="return confirm('Are you sure you want to approve this appraisal?')">
                                    <i class="fas fa-check-circle me-2"></i><span class="d-none d-sm-inline">Approve</span><span class="d-sm-none">✓</span>
                                </button>
                            </form>
                        @endif
                        
                        @if($appraisal->status === 'draft')
                            <form action="{{ route('appraisals.reject', $appraisal->id) }}" method="POST" class="d-inline flex-fill flex-md-grow-0">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-danger btn-sm w-100"
                                        onclick="return confirm('Are you sure you want to reject this appraisal?')">
                                    <i class="fas fa-times-circle me-2"></i><span class="d-none d-sm-inline">Reject</span><span class="d-sm-none">✗</span>
                                </button>
                            </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <main class="py-3 py-md-4">
            <div class="container-custom px-2 px-md-3">
                
                <!-- ============================================= -->
                <!-- PIP BANNER - PROMINENT LINK TO INITIATE PIP -->
                <!-- ============================================= -->
                @if($requiresPIP && $isSupervisor)
                <div class="pip-warning-banner d-flex flex-wrap align-items-center justify-content-between gap-3 no-print">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pip-icon" style="width: 2.5rem; height: 2.5rem; background: #e7581c; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line fa-lg text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold" style="color:#9a3412;">
                                <i class="fas fa-exclamation-triangle me-2"></i>Performance Improvement Plan Required
                            </h5>
                            <p class="mb-0 small text-dark">
                                Final score: <strong class="text-danger">{{ number_format($totalScore, 1) }}%</strong> (below 75% threshold). 
                                Employee requires formal Performance Improvement Plan.
                            </p>
                        </div>
                    </div>
                    <button onclick="initiatePIP()" class="btn btn-pip px-4 py-2">
                        <i class="fas fa-file-signature me-2"></i>Initiate PIP Now
                    </button>
                </div>
                @elseif($pipActive)
                <div class="pip-warning-banner pip-active-banner d-flex flex-wrap align-items-center justify-content-between gap-3 no-print">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pip-icon" style="width: 2.5rem; height: 2.5rem; background: #9a3412; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line fa-lg text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold" style="color:#9a3412;">
                                <i class="fas fa-clipboard-list me-2"></i>Performance Improvement Plan Active
                            </h5>
                            <p class="mb-0 small text-dark">
                                PIP Period: {{ \Carbon\Carbon::parse($appraisal->pip_start_date)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($appraisal->pip_end_date)->format('M d, Y') }}
                                @if($appraisal->pip_plan)
                                <br><small class="text-muted"><i class="fas fa-file-alt me-1"></i>Plan details available</small>
                                @endif
                            </p>
                        </div>
                    </div>
                    <button onclick="viewPIPDetails()" class="btn btn-pip-outline px-4 py-2">
                        <i class="fas fa-eye me-2"></i>View PIP Details
                    </button>
                </div>
                @endif

                <!-- Appraisal Details Header -->
                <div class="card card-moic mb-3 mb-md-4">
                    <div class="card-body">
                        <div class="row g-2 g-md-3">
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-blue-50 me-2 me-md-3" style="width: 2.5rem; height: 2.5rem;">
                                        <i class="fas fa-calendar-alt moic-navy" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted small mb-0 mb-md-1">Period</p>
                                        <p class="fw-semibold mb-0 small small-md-normal">{{ $appraisal->period }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-green-50 me-2 me-md-3" style="width: 2.5rem; height: 2.5rem;">
                                        <i class="fas fa-clock text-success" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted small mb-0 mb-md-1">Duration</p>
                                        <p class="fw-semibold mb-0 small small-md-normal">
                                            <span class="d-none d-md-inline">{{ \Carbon\Carbon::parse($appraisal->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($appraisal->end_date)->format('M d, Y') }}</span>
                                            <span class="d-md-none">{{ \Carbon\Carbon::parse($appraisal->start_date)->format('m/d') }}-{{ \Carbon\Carbon::parse($appraisal->end_date)->format('m/d/y') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-purple-50 me-2 me-md-3" style="width: 2.5rem; height: 2.5rem;">
                                        <i class="fas fa-tag text-purple-600" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted small mb-0 mb-md-1">Type</p>
                                        <p class="fw-semibold mb-0 small small-md-normal">{{ $appraisal->appraisal_type ?? 'Annual' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-amber-50 me-2 me-md-3" style="width: 2.5rem; height: 2.5rem;">
                                        <i class="fas fa-calendar-plus moic-accent" style="font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted small mb-0 mb-md-1">Created</p>
                                        <p class="fw-semibold mb-0 small small-md-normal">{{ \Carbon\Carbon::parse($appraisal->created_at)->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Summary -->
                <div class="card card-moic mb-3 mb-md-4">
                    <div class="card-header bg-white border-bottom bg-gray-50 py-2 py-md-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h2 class="h6 h5-md fw-bold moic-navy mb-0">
                                <i class="fas fa-chart-line me-2 moic-accent"></i>Performance Summary
                            </h2>
                            <!-- PIP Link Button in Summary Header -->
                            @if($requiresPIP && $isSupervisor)
                            <button onclick="initiatePIP()" class="btn btn-pip btn-sm">
                                <i class="fas fa-file-medical me-1"></i>Initiate PIP
                            </button>
                            @elseif($pipActive)
                            <button onclick="viewPIPDetails()" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-clipboard-list me-1"></i>View PIP
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            // Calculate scores
                            $totalWeight = $appraisal->kpas->sum('weight');
                            
                            // Calculate individual KPA scores
                            $individualScores = [];
                            $totalSelfScore = 0;
                            $totalSupervisorScore = 0;
                            
                            foreach($appraisal->kpas as $kpa) {
                                $kpi = $kpa->kpi ?: 4;
                                
                                // Self score calculation
                                $selfScore = ($kpa->self_rating / $kpi) * $kpa->weight;
                                $totalSelfScore += $selfScore;
                                
                                // For multiple supervisors, get the final weighted average
                                if(isset($hasMultipleSupervisors) && $hasMultipleSupervisors) {
                                    // Get all supervisor ratings for this KPA
                                    $ratings = collect();
                                    if (method_exists($kpa, 'ratedSupervisors')) {
                                        $ratings = $kpa->ratedSupervisors();
                                    } elseif (isset($kpa->ratings)) {
                                        $ratings = $kpa->ratings;
                                    }
                                    
                                    if ($ratings->count() > 0) {
                                        // Calculate weighted average based on supervisor weights
                                        $totalWeightedRating = 0;
                                        $totalWeightSum = 0;
                                        
                                        foreach($ratings as $rating) {
                                            $supervisorId = $rating->supervisor_id ?? null;
                                            $supervisorWeight = 100; // default weight
                                            
                                            // Find supervisor weight from ratingSupervisors collection
                                            if (isset($ratingSupervisors)) {
                                                foreach($ratingSupervisors as $sup) {
                                                    $supId = $sup->employee_number ?? $sup->id ?? null;
                                                    if ($supId && $supervisorId && $supId == $supervisorId) {
                                                        $supervisorWeight = $sup->pivot->rating_weight ?? 100;
                                                        break;
                                                    }
                                                }
                                            }
                                            
                                            $totalWeightedRating += ($rating->rating * $supervisorWeight);
                                            $totalWeightSum += $supervisorWeight;
                                        }
                                        
                                        $finalRating = $totalWeightSum > 0 ? $totalWeightedRating / $totalWeightSum : 0;
                                    } else {
                                        $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                    }
                                } else {
                                    $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                }
                                
                                $supervisorScore = ($finalRating / $kpi) * $kpa->weight;
                                $totalSupervisorScore += $supervisorScore;
                                $individualScores[] = $supervisorScore;
                            }
                            
                            // Calculate total score
                            $totalScore = $totalSupervisorScore;
                            
                            $ratedCount = 0;
                            $totalCount = $appraisal->kpas->count();
                            
                            foreach($appraisal->kpas as $kpa) {
                                if(isset($hasMultipleSupervisors) && $hasMultipleSupervisors) {
                                    if (method_exists($kpa, 'ratedSupervisors') && $kpa->ratedSupervisors()->count() > 0) {
                                        $ratedCount++;
                                    } elseif (isset($kpa->ratings) && $kpa->ratings->count() > 0) {
                                        $ratedCount++;
                                    } elseif ($kpa->supervisor_rating) {
                                        $ratedCount++;
                                    }
                                } else {
                                    if($kpa->supervisor_rating) {
                                        $ratedCount++;
                                    }
                                }
                            }
                            
                            // Determine performance level
                            $performanceLevel = '';
                            $performanceColor = '';
                            if ($totalScore >= 90) {
                                $performanceLevel = 'Excellent';
                                $performanceColor = 'score-excellent';
                            } elseif ($totalScore >= 70) {
                                $performanceLevel = 'Good';
                                $performanceColor = 'score-good';
                            } elseif ($totalScore >= 50) {
                                $performanceLevel = 'Fair';
                                $performanceColor = 'score-fair';
                            } else {
                                $performanceLevel = 'Needs Improvement';
                                $performanceColor = 'score-poor';
                            }
                        @endphp
                        
                        <div class="row g-2 g-md-4">
                            <!-- Performance Stats -->
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-block">
                                    <div class="summary-stat-label">Total Weight</div>
                                    <div class="summary-stat-value moic-navy">{{ $totalWeight }}%</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-block">
                                    <div class="summary-stat-label">Self Score</div>
                                    <div class="summary-stat-value text-primary">{{ number_format($totalSelfScore, 1) }}%</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-block {{ $totalScore < 75 ? 'border-danger' : '' }}">
                                    <div class="summary-stat-label">Final Score</div>
                                    <div class="summary-stat-value {{ $totalScore < 75 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($totalScore, 1) }}%
                                    </div>
                                    <span class="small {{ $performanceColor }} d-none d-md-inline">{{ $performanceLevel }}</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="summary-stat-block {{ $pipActive ? 'border-warning' : ($requiresPIP ? 'border-danger' : '') }}">
                                    <div class="summary-stat-label">PIP Status</div>
                                    <div class="summary-stat-value {{ $pipActive ? 'text-warning' : ($requiresPIP ? 'text-danger' : 'text-success') }}">
                                        @if($pipActive)
                                            <i class="fas fa-chart-line fa-sm me-1"></i>Active
                                        @elseif($requiresPIP)
                                            <i class="fas fa-exclamation-triangle fa-sm me-1"></i>Required
                                        @else
                                            <i class="fas fa-check-circle fa-sm me-1"></i>Not Required
                                        @endif
                                    </div>
                                    @if($requiresPIP && $isSupervisor)
                                    <div class="mt-2">
                                        <button onclick="initiatePIP()" class="btn btn-pip btn-sm w-100">
                                            <i class="fas fa-plus-circle me-1"></i>Initiate PIP
                                        </button>
                                    </div>
                                    @endif
                                    @if($pipActive && $appraisal->pip_end_date)
                                    <span class="small text-muted d-block mt-1">
                                        Until {{ \Carbon\Carbon::parse($appraisal->pip_end_date)->format('M d, Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Performance Level -->
                        <div class="d-md-none text-center mt-2">
                            <span class="performance-rating-badge {{ $performanceColor }}">{{ $performanceLevel }}</span>
                        </div>
                        
                        <!-- Performance Progress Bar -->
                        <div class="mt-3 mt-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small fw-medium text-muted">Overall Performance</span>
                                <span class="small fw-medium {{ $performanceColor }} d-none d-md-inline">{{ $performanceLevel }}</span>
                                <span class="small fw-medium {{ $performanceColor }} d-md-none">{{ number_format($totalScore, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 0.6rem; height: clamp(0.5rem, 2vw, 0.75rem); background-color: #e9ecef;">
                                <div class="progress-bar" 
                                     style="width: {{ min($totalScore, 100) }}%; background: linear-gradient(90deg, var(--moic-navy), var(--moic-accent));"
                                     role="progressbar" 
                                     aria-valuenow="{{ min($totalScore, 100) }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mt-1 mt-md-2">
                                <span>0%</span>
                                <span class="fw-bold text-primary d-none d-md-inline">{{ number_format($totalScore, 1) }}%</span>
                                <span>100%</span>
                            </div>
                        </div>
                        
                        <!-- PIP Threshold Warning Line -->
                        @if($totalScore < 75)
                        <div class="alert alert-danger alert-sm mt-3 mb-0 py-2 no-print">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Below Performance Threshold:</strong> Score is below 75%. 
                            @if($isSupervisor && !$pipActive)
                            <a href="javascript:void(0)" onclick="initiatePIP()" class="alert-link">Click here to initiate Performance Improvement Plan</a>.
                            @elseif($pipActive)
                            Performance Improvement Plan is currently active.
                            @else
                            Supervisor review required.
                            @endif
                        </div>
                        @endif
                        
                        <!-- Multiple Supervisors Overview -->
                        @if(isset($hasMultipleSupervisors) && $hasMultipleSupervisors)
                        <div class="mt-3 mt-md-4 p-2 p-md-3 bg-purple-50 rounded border border-purple-200">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-users text-purple-600 mt-1 me-2 me-md-3"></i>
                                <div class="flex-grow-1">
                                    <p class="fw-semibold text-purple-700 mb-1 mb-md-2 small small-md-normal">Multiple Supervisors Assigned</p>
                                    <p class="small text-purple-600 mb-2 mb-md-3">
                                        {{ $ratingSupervisors->count() ?? 0 }} rating supervisors
                                    </p>
                                    
                                    <div class="row g-2 g-md-3">
                                        @foreach($ratingSupervisors ?? [] as $supervisor)
                                            @php
                                                $ratedKPAs = 0;
                                                $supervisorRatings = collect();
                                                $totalRatingSum = 0;
                                                
                                                // Get ALL possible supervisor IDs
                                                $supervisorIds = [];
                                                
                                                if (isset($supervisor->employee_number) && $supervisor->employee_number) {
                                                    $supervisorIds[] = $supervisor->employee_number;
                                                }
                                                if (isset($supervisor->id) && $supervisor->id) {
                                                    $supervisorIds[] = $supervisor->id;
                                                }
                                                if (isset($supervisor->pivot) && isset($supervisor->pivot->supervisor_id)) {
                                                    $supervisorIds[] = $supervisor->pivot->supervisor_id;
                                                }
                                                if (isset($supervisor->supervisor_id)) {
                                                    $supervisorIds[] = $supervisor->supervisor_id;
                                                }
                                                
                                                $supervisorIds = array_unique($supervisorIds);
                                                
                                                foreach($appraisal->kpas as $kpa) {
                                                    $ratingFound = false;
                                                    
                                                    $ratings = collect();
                                                    if (isset($kpa->ratings) && $kpa->ratings) {
                                                        $ratings = $kpa->ratings;
                                                    } elseif (method_exists($kpa, 'ratedSupervisors')) {
                                                        $ratings = $kpa->ratedSupervisors();
                                                    }
                                                    
                                                    foreach($ratings as $rating) {
                                                        $ratingSupervisorId = null;
                                                        
                                                        if (isset($rating->supervisor_id)) {
                                                            $ratingSupervisorId = $rating->supervisor_id;
                                                        } elseif (isset($rating->supervisor) && isset($rating->supervisor->id)) {
                                                            $ratingSupervisorId = $rating->supervisor->id;
                                                        } elseif (isset($rating->supervisor) && isset($rating->supervisor->employee_number)) {
                                                            $ratingSupervisorId = $rating->supervisor->employee_number;
                                                        }
                                                        
                                                        if ($ratingSupervisorId && in_array($ratingSupervisorId, $supervisorIds)) {
                                                            $ratedKPAs++;
                                                            $supervisorRatings->push($rating);
                                                            $totalRatingSum += $rating->rating ?? $rating->score ?? 0;
                                                            $ratingFound = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                
                                                $ratingPercentage = $totalCount > 0 
                                                    ? round(($ratedKPAs / $totalCount) * 100, 1)
                                                    : 0;
                                                
                                                $currentUserId = auth()->user()->employee_number ?? auth()->user()->id;
                                                $isCurrentSupervisor = $currentUserId && in_array($currentUserId, $supervisorIds);
                                                
                                                $supervisorName = getSupervisorName($supervisor);
                                                
                                                $ratingWeight = 100;
                                                if (isset($supervisor->pivot) && isset($supervisor->pivot->rating_weight)) {
                                                    $ratingWeight = $supervisor->pivot->rating_weight;
                                                }
                                                
                                                $avgRating = $supervisorRatings->count() > 0 
                                                    ? round($totalRatingSum / $supervisorRatings->count(), 1) 
                                                    : 0;
                                            @endphp
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                                                <div class="bg-white p-2 p-md-3 rounded border {{ $isCurrentSupervisor ? 'border-purple-400 shadow-sm' : 'border-purple-100' }}">
                                                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                                        <span class="small fw-medium" title="{{ $supervisorName }}">
                                                            <i class="fas fa-user-circle me-1 text-purple-600"></i>
                                                            <span class="d-none d-sm-inline">{{ Str::limit($supervisorName, 10) }}</span>
                                                            <span class="d-sm-none">{{ Str::limit($supervisorName, 6) }}</span>
                                                            @if($isCurrentSupervisor)
                                                                <span class="badge bg-purple-600 text-white ms-1" style="font-size: 0.6rem;">You</span>
                                                            @endif
                                                        </span>
                                                        <span class="badge bg-purple-100 text-purple-800" style="font-size: 0.6rem;">
                                                            {{ $ratingWeight }}%
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Progress Bar -->
                                                    <div class="mt-2">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="small text-muted">Progress</span>
                                                            <span class="small fw-bold {{ $ratedKPAs > 0 ? 'text-success' : 'text-muted' }}">
                                                                {{ $ratedKPAs }}/{{ $totalCount }}
                                                            </span>
                                                        </div>
                                                        <div class="progress" style="height: 0.6rem; background-color: #e9ecef;">
                                                            <div class="progress-bar {{ $ratedKPAs == $totalCount ? 'bg-success' : ($ratedKPAs > 0 ? 'bg-primary' : 'bg-secondary') }}" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $ratingPercentage }}%;" 
                                                                 aria-valuenow="{{ $ratingPercentage }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Rating Stats -->
                                                    @if($ratedKPAs > 0)
                                                        <div class="mt-2 pt-1 border-top border-purple-100">
                                                            <div class="d-flex justify-content-between small">
                                                                <span class="text-muted">Avg:</span>
                                                                <span class="fw-bold text-purple-700">{{ $avgRating }}/4</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between small mt-1">
                                                                <span class="text-muted">Score:</span>
                                                                <span class="fw-bold text-success">{{ round(($avgRating/4)*100, 1) }}%</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center mt-2">
                                                            <small class="text-muted fst-italic">No ratings</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- KPA Table -->
                <div class="card card-moic mb-3 mb-md-4">
                    <div class="card-header bg-white border-bottom bg-gray-50 py-2 py-md-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h2 class="h6 h5-md fw-bold moic-navy mb-0 kpi-section-title">
                                <i class="fas fa-tasks me-2 moic-accent"></i>Key Performance Areas (KPAs)
                            </h2>
                            @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i><span class="d-none d-sm-inline">Supervisor Rating Required</span><span class="d-sm-inline">Rate Required</span>
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($appraisal->kpas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-moic mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>KPA Details</th>
                                    <th>Weight</th>
                                    <th>Self Rating</th>
                                    
                                    @if(isset($hasMultipleSupervisors) && $hasMultipleSupervisors)
                                        <th>Final Rating</th>
                                        <th>Score</th>
                                        <th>Ratings Summary</th>
                                    @else
                                        <th>Supervisor Rating</th>
                                        <th>Score</th>
                                        <th>Comments</th>
                                    @endif
                                    
                                    @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                                        <th>Actions</th>
                                    @endif
                                 </>
                            </thead>
                            <tbody>
                                @foreach($appraisal->kpas as $kpa)
                                @php
                                    $kpi = $kpa->kpi ?: 4;
                                    
                                    if(isset($hasMultipleSupervisors) && $hasMultipleSupervisors && method_exists($kpa, 'getFinalSupervisorRatingAttribute')) {
                                        $finalRating = $kpa->getFinalSupervisorRatingAttribute();
                                        $supervisorRatings = method_exists($kpa, 'ratedSupervisors') ? $kpa->ratedSupervisors() : collect();
                                        $currentSupervisorHasRated = method_exists($kpa, 'hasSupervisorRating') 
                                            ? $kpa->hasSupervisorRating(auth()->user()->employee_number)
                                            : false;
                                    } else {
                                        $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                    }
                                    
                                    $individualScore = ($finalRating / $kpi) * $kpa->weight;
                                    
                                    $scoreColor = '';
                                    if ($individualScore >= ($kpa->weight * 0.9)) {
                                        $scoreColor = 'score-excellent';
                                    } elseif ($individualScore >= ($kpa->weight * 0.7)) {
                                        $scoreColor = 'score-good';
                                    } elseif ($individualScore >= ($kpa->weight * 0.5)) {
                                        $scoreColor = 'score-fair';
                                    } else {
                                        $scoreColor = 'score-poor';
                                    }
                                @endphp
                                <tr>
                                    <td data-label="Category">{{ $kpa->category }}</td>
                                    <td data-label="KPA Details">
                                        <div class="kpa-details">
                                            <div class="kpa-title">{{ $kpa->kpa }}</div>
                                            @if($kpa->description)
                                                <div class="kpa-description d-none d-md-block">{{ $kpa->description }}</div>
                                            @endif
                                            @if($kpa->result_indicators)
                                                <div class="kpa-indicators mt-1" title="Result Indicators">
                                                    <i class="fas fa-chart-line me-1" style="font-size: 0.7rem;"></i>
                                                    <span class="d-none d-md-inline">{{ $kpa->result_indicators }}</span>
                                                    <span class="d-md-none">{{ Str::limit($kpa->result_indicators, 30) }}</span>
                                                </div>
                                            @endif
                                            <div class="kpa-metrics">
                                                @if($kpa->target)
                                                    <span class="kpa-target">
                                                        <i class="fas fa-bullseye me-1"></i>T:{{ $kpa->target }}
                                                    </span>
                                                @endif
                                                @if($kpa->actual_achievement)
                                                    <span class="kpa-actual">
                                                        <i class="fas fa-check-circle me-1"></i>A:{{ $kpa->actual_achievement }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <td data-label="Weight">{{ $kpa->weight }}%</div>
                                    <td data-label="Self Rating">
                                        {{ $kpa->self_rating }}/{{ $kpi }}
                                        <small class="text-muted d-block d-md-none">
                                            {{ number_format(($kpa->self_rating / $kpi) * 100, 1) }}%
                                        </small>
                                    </div>
                                    
                                    @if(isset($hasMultipleSupervisors) && $hasMultipleSupervisors)
                                        <!-- Multiple supervisors view -->
                                        <td data-label="Final Rating">
                                            <span class="fw-bold {{ $scoreColor }}">
                                                {{ number_format($finalRating, 1) }}/{{ $kpi }}
                                            </span>
                                         </div>
                                        <td data-label="Score">
                                            <span class="fw-bold {{ $scoreColor }}">
                                                {{ number_format($individualScore, 1) }}%
                                            </span>
                                         </div>
                                        <td data-label="Ratings">
                                            @if($supervisorRatings->isNotEmpty())
                                                <span class="badge bg-info">{{ $supervisorRatings->count() }} ratings</span>
                                                <button onclick="viewAllRatings({{ $kpa->id }})"
                                                        class="btn btn-sm btn-link p-0 ms-2 no-print">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @else
                                                <span class="text-muted fst-italic small">No ratings</span>
                                            @endif
                                         </div>
                                    @else
                                        <!-- Single supervisor view -->
                                        <td data-label="Supervisor Rating">
                                            @if($kpa->supervisor_rating)
                                                {{ $kpa->supervisor_rating }}/{{ $kpi }}
                                                <span class="badge bg-success bg-opacity-10 text-success small ms-1 d-none d-md-inline-block">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @else
                                                <span class="text-muted fst-italic small">— Not Rated —</span>
                                            @endif
                                         </div>
                                        <td data-label="Score">
                                            <span class="fw-bold {{ $scoreColor }}">
                                                {{ number_format($individualScore, 1) }}%
                                            </span>
                                         </div>
                                        <td data-label="Comments">
                                            @if($kpa->supervisor_comments || $kpa->comments)
                                                <i class="fas fa-comment text-muted me-1"></i>
                                                <span class="small">Comments</span>
                                                @if($kpa->supervisor_comments || $kpa->comments)
                                                    @php
                                                        $commentAuthor = $kpa->supervisor_comments ? 'Supervisor' : 'Employee';
                                                        $commentContent = $kpa->supervisor_comments ?? $kpa->comments;
                                                        $commentRating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                                        $commentDate = $kpa->updated_at;
                                                    @endphp
                                                    <button onclick="showFullCommentWithRating('{{ addslashes($commentAuthor) }} Comment', {{ json_encode($commentContent) }}, '{{ addslashes($commentAuthor) }}', '{{ $commentDate }}', '{{ $commentRating }}/{{ $kpi }}')" 
                                                            class="btn btn-sm btn-link p-0 ms-1 no-print">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-muted fst-italic small">—</span>
                                            @endif
                                         </div>
                                    @endif
                                    
                                    @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                                        <td data-label="Actions" class="no-print">
                                            <div class="d-flex flex-wrap gap-1">
                                                @if(isset($hasMultipleSupervisors) && $hasMultipleSupervisors)
                                                    @if($currentSupervisorHasRated)
                                                        <button onclick="showKPARatingModal({{ $kpa->id }}, '{{ addslashes($kpa->category) }}', {{ json_encode($kpa->kpa) }}, {{ $kpi }}, {{ $kpa->self_rating }})"
                                                                class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    @else
                                                        <button onclick="showKPARatingModal({{ $kpa->id }}, '{{ addslashes($kpa->category) }}', {{ json_encode($kpa->kpa) }}, {{ $kpi }}, {{ $kpa->self_rating }})"
                                                                class="btn btn-sm btn-warning">
                                                            <i class="fas fa-star"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <button onclick="showKPARatingModal({{ $kpa->id }}, '{{ addslashes($kpa->category) }}', {{ json_encode($kpa->kpa) }}, {{ $kpi }}, {{ $kpa->self_rating }})"
                                                            class="btn btn-sm btn-warning">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                            </div>
                                         </div>
                                    @endif
                                 </>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 fw-bold">
                                 <tr>
                                    <td colspan="{{ isset($hasMultipleSupervisors) && $hasMultipleSupervisors ? '3' : '2' }}" class="text-end">
                                        Total Score:
                                     </div>
                                    <td colspan="2" class="{{ $totalScore < 75 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($totalScore, 1) }}%
                                        @if($totalScore < 75)
                                        <i class="fas fa-exclamation-triangle ms-1" title="Below threshold - PIP Required"></i>
                                        @endif
                                      </div>
                                    @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                                        <td class="no-print"></div>
                                    @endif
                                 </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="card-body text-center py-4 py-md-5">
                        <i class="fas fa-tasks fa-2x fa-md-3x text-muted mb-2 mb-md-3"></i>
                        <p class="text-muted">No KPAs added to this appraisal yet.</p>
                    </div>
                    @endif
                </div>

                <!-- Comments Section with Expandable Cards - Enhanced for report format -->
                <div class="row g-3 g-md-4 mb-3 mb-md-4">
                    <div class="col-md-6">
                        <div class="comment-section-card h-100">
                            <div class="card-header bg-white border-bottom bg-blue-50 py-2 py-md-3">
                                <h3 class="h6 fw-bold text-primary mb-0">
                                    <i class="fas fa-user me-2"></i>Employee Reflections & Development
                                </h3>
                            </div>
                            <div class="card-body p-2 p-md-3">
                                @if($appraisal->development_needs)
                                    <div class="comment-card employee-comment p-2 p-md-3" 
                                         onclick="showFullCommentModal('Development Needs', {{ $developmentNeedsJson }}, 'Employee', '{{ $appraisal->updated_at }}')">
                                        <div class="comment-header">
                                            <span class="comment-author">
                                                <i class="fas fa-graduation-cap me-1 text-primary"></i>Development Needs
                                            </span>
                                            <span class="comment-date">{{ \Carbon\Carbon::parse($appraisal->updated_at)->format('M d, Y') }}</span>
                                        </div>
                                        <div class="comment-preview">{{ Str::limit($appraisal->development_needs, 80) }}</div>
                                        @if(strlen($appraisal->development_needs) > 80)
                                        <button class="comment-expand-btn no-print" 
                                                onclick="event.stopPropagation(); showFullCommentModal('Development Needs', {{ $developmentNeedsJson }}, 'Employee', '{{ $appraisal->updated_at }}')">
                                            <i class="fas fa-expand-alt me-1"></i>Read full
                                        </button>
                                        @endif
                                    </div>
                                @endif

                                @if($appraisal->employee_comments)
                                    <div class="comment-card employee-comment p-2 p-md-3" 
                                         onclick="showFullCommentModal('Additional Comments', {{ $employeeCommentsJson }}, 'Employee', '{{ $appraisal->updated_at }}')">
                                        <div class="comment-header">
                                            <span class="comment-author">
                                                <i class="fas fa-comment me-1 text-primary"></i>Additional Comments
                                            </span>
                                            <span class="comment-date">{{ \Carbon\Carbon::parse($appraisal->updated_at)->format('M d, Y') }}</span>
                                        </div>
                                        <div class="comment-preview">{{ Str::limit($appraisal->employee_comments, 80) }}</div>
                                        @if(strlen($appraisal->employee_comments) > 80)
                                        <button class="comment-expand-btn no-print" 
                                                onclick="event.stopPropagation(); showFullCommentModal('Additional Comments', {{ $employeeCommentsJson }}, 'Employee', '{{ $appraisal->updated_at }}')">
                                            <i class="fas fa-expand-alt me-1"></i>Read full
                                        </button>
                                        @endif
                                    </div>
                                @endif

                                @if(!$appraisal->development_needs && !$appraisal->employee_comments)
                                    <p class="text-muted fst-italic text-center py-3 mb-0">No reflections or comments provided.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="comment-section-card h-100">
                            <div class="card-header bg-white border-bottom bg-green-50 py-2 py-md-3">
                                <h3 class="h6 fw-bold text-success mb-0">
                                    <i class="fas fa-user-tie me-2"></i>Supervisor Assessment & Feedback
                                </h3>
                            </div>
                            <div class="card-body p-2 p-md-3">
                                @if($appraisal->supervisor_comments)
                                    <div class="comment-card supervisor-comment p-2 p-md-3" 
                                         onclick="showFullCommentModal('Supervisor Comments', {{ $supervisorCommentsJson }}, '{{ $appraisal->approved_by ?? 'Supervisor' }}', '{{ $appraisal->approved_at ?? $appraisal->updated_at }}')">
                                        <div class="comment-header">
                                            <span class="comment-author">
                                                <i class="fas fa-user-tie me-1 text-success"></i>
                                                {{ $appraisal->approved_by ?? 'Supervisor' }}
                                            </span>
                                            <span class="comment-date">
                                                @if($appraisal->approved_at)
                                                    {{ \Carbon\Carbon::parse($appraisal->approved_at)->format('M d, Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($appraisal->updated_at)->format('M d, Y') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="comment-preview">{{ Str::limit($appraisal->supervisor_comments, 80) }}</div>
                                        @if(strlen($appraisal->supervisor_comments) > 80)
                                        <button class="comment-expand-btn no-print" 
                                                onclick="event.stopPropagation(); showFullCommentModal('Supervisor Comments', {{ $supervisorCommentsJson }}, '{{ $appraisal->approved_by ?? 'Supervisor' }}', '{{ $appraisal->approved_at ?? $appraisal->updated_at }}')">
                                            <i class="fas fa-expand-alt me-1"></i>Read full
                                        </button>
                                        @endif
                                    </div>
                                @endif

                                @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                                    <form action="{{ route('appraisals.add-comment', $appraisal->id) }}" method="POST" class="mt-3 no-print">
                                        @csrf
                                        <div class="mb-2 mb-md-3">
                                            <textarea name="supervisor_comments" 
                                                      rows="2" 
                                                      class="form-control form-control-sm"
                                                      placeholder="Add overall feedback..."
                                                      required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success w-100 w-md-auto">
                                            <i class="fas fa-comment me-2"></i>Add Feedback
                                        </button>
                                    </form>
                                @elseif(!$appraisal->supervisor_comments)
                                    <p class="text-muted fst-italic text-center py-3 mb-0">No supervisor feedback recorded.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="border-top mt-3 mt-md-4 pt-3 pt-md-4 no-print">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-2 mb-lg-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                                <div class="bg-white p-2 rounded me-3">
                                    <img class="img-fluid" style="height: 1.25rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                                </div>
                                <div class="text-center text-lg-start">
                                    <p class="text-muted small mb-0">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                                    <p class="text-muted small d-none d-md-block">Version 1.0.0 powered by SmartWave Solutions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-wrap justify-content-center justify-content-lg-end gap-3 gap-md-4">
                                <a href="#" class="text-decoration-none text-muted small">
                                    <i class="fas fa-question-circle me-1"></i><span class="d-none d-sm-inline">Help</span>
                                </a>
                                <a href="#" class="text-decoration-none text-muted small">
                                    <i class="fas fa-shield-alt me-1"></i><span class="d-none d-sm-inline">Privacy</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </main>
    </div>

    <!-- Comment View Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--moic-gradient); color: white;">
                    <h5 class="modal-title" id="commentModalTitle">Full Comment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="comment-meta">
                        <span class="comment-meta-item">
                            <i class="fas fa-user"></i>
                            <span id="commentModalAuthor"></span>
                        </span>
                        <span class="comment-meta-item">
                            <i class="fas fa-calendar"></i>
                            <span id="commentModalDate"></span>
                        </span>
                        <span class="comment-meta-item" id="commentModalRatingContainer" style="display: none;">
                            <i class="fas fa-star text-warning"></i>
                            <span id="commentModalRating"></span>
                        </span>
                    </div>
                    <div class="comment-full-content" id="commentModalContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PIP Initiation Modal with 90-Day Template Integration -->
    <div class="modal fade" id="pipModal" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-xl" style="max-height: 90vh; margin: 1rem auto;">
            <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
                <div class="modal-header" style="background: linear-gradient(135deg, #e7581c, #c2410c); color: white; flex-shrink: 0;">
                    <h5 class="modal-title">
                        <i class="fas fa-file-contract me-2"></i>Initiate 90-Day Performance Improvement Plan (PIP)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pipForm" method="POST" action="{{ route('appraisals.initiate-pip', $appraisal->id) }}" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                    @csrf
                    <div class="modal-body" style="flex: 1; overflow-y: auto; padding: 1.5rem;">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Employee final score: <strong>{{ number_format($totalScore, 1) }}%</strong> (below required 75% threshold).
                        </div>
                        
                        <!-- 90-Day PIP Supplementary Information Section -->
                        <div class="card mb-3 border-pip">
                            <div class="card-header bg-light fw-bold">PIP Supplementary Information (Ref PM018 | Rev 0, Aug 2024)</div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Employee Name</label>
                                        <input type="text" class="form-control" value="{{ $appraisal->user->name ?? $appraisal->employee_name ?? 'System Employee' }}" readonly disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Date</label>
                                        <input type="text" class="form-control" value="{{ now()->format('F d, Y') }}" readonly disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Job Title</label>
                                        <input type="text" class="form-control" value="{{ $appraisal->job_title ?? $appraisal->position ?? 'Staff' }}" readonly disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Department</label>
                                        <input type="text" class="form-control" value="{{ $appraisal->department ?? 'Operations' }}" readonly disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Manager Name</label>
                                        <input type="text" class="form-control" value="{{ auth()->user()->name ?? 'Supervisor' }}" readonly disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Hired Date</label>
                                        <input type="text" class="form-control" value="{{ $appraisal->hired_date ?? ($appraisal->start_date ?? 'N/A') }}" readonly disabled>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Area of Concern</label>
                                        <textarea class="form-control" rows="2" readonly disabled>Performance score {{ number_format($totalScore, 1) }}% below 75% threshold. Specific KPAs require immediate improvement.</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Goal or Plan for Improvement</label>
                                        <input type="text" class="form-control" value="Achieve minimum 85% on all core KPIs by end of PIP period." readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Plan Table -->
                        <div class="card mb-3">
                            <div class="card-header bg-light fw-bold">Action Plan & Objectives</div>
                            <div class="card-body">
                                <table class="pip-action-table" style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr><th style="border: 1px solid #dee2e6; padding: 8px;">Action</th><th style="border: 1px solid #dee2e6; padding: 8px;">Objective</th><th style="border: 1px solid #dee2e6; padding: 8px;">Measurement</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="action_1" class="form-control" placeholder="e.g., Weekly progress reports" value=""></td>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="objective_1" class="form-control" placeholder="Objective" value=""></td>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="measure_1" class="form-control" placeholder="Measurement" value=""></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="action_2" class="form-control" placeholder="e.g., Attend skill enhancement workshop" value=""></td>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="objective_2" class="form-control" placeholder="Objective" value=""></td>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="measure_2" class="form-control" placeholder="Measurement" value=""></td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="action_3" class="form-control" placeholder="e.g., Improve quality metrics" value=""></td>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="objective_3" class="form-control" placeholder="Objective" value=""></td>
                                            <td style="border: 1px solid #dee2e6; padding: 8px;"><input type="text" name="measure_3" class="form-control" placeholder="Measurement" value=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <small class="text-muted">Customize actions, objectives and measurements as needed.</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">PIP Start Date</label>
                            <input type="date" name="pip_start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">PIP End Date (90-Day Review Period)</label>
                            <input type="date" name="pip_end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+90 days')) }}" required>
                            <small class="text-muted">Standard 90-day improvement period</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Improvement Objectives & Action Plan (Detailed)</label>
                            <textarea name="pip_plan" rows="4" class="form-control" required 
                                placeholder=""></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Supervisor Comments / Expectations & Follow-up Plan</label>
                            <textarea name="pip_supervisor_notes" rows="3" class="form-control" 
                                placeholder=""></textarea>
                        </div>
                        
                        <!-- Signatures section -->
                        <div class="row mt-3 pt-2 border-top">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Employee Signature</label>
                                <div class="border-bottom mt-1" style="width: 90%;">&nbsp;</div>
                              <small class="text-muted">{{$appraisal->user->name ?? $appraisal->employee_name ?? 'System Employee' }}</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Manager Signature</label>
                                <div class="border-bottom mt-1" style="width: 90%;">&nbsp;</div>
                                <small class="text-muted">{{ auth()->user()->name ?? 'Supervisor' }}</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date</label>
                                <div class="border-bottom mt-1" style="width: 90%;">&nbsp;</div>
                                <small>{{ now()->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background: #f8f9fa; border-top: 1px solid #dee2e6; padding: 1rem; flex-shrink: 0;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-pip" style="background: linear-gradient(135deg, #e7581c, #c2410c); border: none; padding: 0.5rem 1.5rem;">
                            <i class="fas fa-save me-2"></i>Submit 90-Day PIP & Notify Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- IMPROVED PIP DETAILS MODAL - With Follow-up Comments and Prominent Submit Button -->
<div class="modal fade" id="pipDetailsModal" tabindex="-1" aria-hidden="false">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #9a3412, #7c2d12); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list me-2"></i>Performance Improvement Plan Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    <!-- TOLL PLAZA OPERATIONS: SUPPLEMENTARY INFORMATION FILE -->
    <div class="pip-detail-card" style="border-left: 4px solid #e7581c;">
        <div class="pip-detail-header" style="background: #f8f4f0;">
            <i class="fas fa-file-alt" style="color: #e7581c;"></i>
            <span class="title" style="color: #4b2e1a;">Toll Plaza Operations : Supplementary Information File, Ref PM018 | Rev 0, Aug 2024</span>
        </div>
        <div class="pip-detail-body">
            <!-- Header Row: Employee Name & Date -->
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="fw-bold small text-muted mb-1">Employee Name</label><div class="border-bottom pb-1 fw-semibold">{{ $appraisal->user->name ?? $appraisal->employee_name ?? 'System Employee' }}</div></div>
                <div class="col-md-6"><label class="fw-bold small text-muted mb-1">Date</label><div class="border-bottom pb-1 fw-semibold">{{ now()->format('F d, Y') }}</div></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="fw-bold small text-muted mb-1">Job Title</label><div class="border-bottom pb-1">{{ $appraisal->job_title ?? $appraisal->position ?? 'Staff' }}</div></div>
                <div class="col-md-6"><label class="fw-bold small text-muted mb-1">Department</label><div class="border-bottom pb-1">{{ $appraisal->department ?? 'Operations' }}</div></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="fw-bold small text-muted mb-1">Manager Name</label><div class="border-bottom pb-1">{{ auth()->user()->name ?? 'Supervisor' }}</div></div>
                <div class="col-md-6"><label class="fw-bold small text-muted mb-1">Hired Date</label><div class="border-bottom pb-1">{{ $appraisal->hired_date ?? ($appraisal->start_date ?? 'N/A') }}</div></div>
            </div>
            
            <div class="mb-3"><label class="fw-bold small text-muted mb-1">Area of Concern</label><div class="bg-light p-2 rounded" style="background: #fefaf5;">Performance score <strong>{{ number_format($totalScore, 1) }}%</strong> below 75% threshold. Specific KPAs require immediate improvement.</div></div>
            <div class="mb-3"><label class="fw-bold small text-muted mb-1">Goal or Plan for Improvement</label><div class="bg-light p-2 rounded" style="background: #fefaf5;">Achieve minimum 85% on all core KPIs by end of PIP period.</div></div>
            
            <!-- Action Plan Table (keep existing) -->
            <div style="margin-bottom: 15px;">
                <label style="font-size: 11px; font-weight: bold; color: #4b2e1a; display: block; margin-bottom: 8px;"><i class="fas fa-tasks me-1"></i> Action Plan & Objectives</label>
                <table style="width: 100%; border-collapse: collapse; font-size: 9.5px; background: white; table-layout: fixed;">
                    <thead><tr style="background: #fefaf5;"><th style="border: 1px solid #f0e2d6; padding: 8px 6px; text-align: left;">Action</th><th style="border: 1px solid #f0e2d6; padding: 8px 6px; text-align: left;">Objective</th><th style="border: 1px solid #f0e2d6; padding: 8px 6px; text-align: left;">Measurement</th></tr></thead>
                    <tbody>
                        @foreach($actionPlan as $item)
                        <tr><td style="border: 1px solid #f0e2d6; padding: 8px 6px;">{{ $item['action'] ?? $item['Action'] ?? '—' }}</td><td style="border: 1px solid #f0e2d6; padding: 8px 6px;">{{ $item['objective'] ?? $item['Objective'] ?? '—' }}</td><td style="border: 1px solid #f0e2d6; padding: 8px 6px;">{{ $item['measurement'] ?? $item['Measurement'] ?? '—' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Timeline Card -->
    <div class="pip-detail-card">
        <div class="pip-detail-header"><i class="far fa-calendar-alt"></i><span class="title">Plan Timeline</span></div>
        <div class="pip-detail-body"><div class="pip-meta-grid"><div class="pip-meta-item"><span class="pip-meta-label">Start Date</span><div class="pip-meta-value">{{ \Carbon\Carbon::parse($appraisal->pip_start_date)->format('F d, Y') }}</div></div><div class="pip-meta-item"><span class="pip-meta-label">End Date</span><div class="pip-meta-value">{{ \Carbon\Carbon::parse($appraisal->pip_end_date)->format('F d, Y') }}</div></div><div class="pip-meta-item"><span class="pip-meta-label">Duration</span><div class="pip-meta-value">{{ \Carbon\Carbon::parse($appraisal->pip_start_date)->diffInDays(\Carbon\Carbon::parse($appraisal->pip_end_date)) }} days</div></div></div></div>
    </div>

    <!-- Improvement Plan Card -->
    <div class="pip-detail-card"><div class="pip-detail-header"><i class="fas fa-tasks"></i><span class="title">Improvement Plan & Action Items</span></div><div class="pip-detail-body"><div class="pip-content-box">{!! nl2br(e($appraisal->pip_plan ?? 'No plan details available.')) !!}</div></div></div>

    @if($appraisal->pip_supervisor_notes)
    <div class="pip-detail-card"><div class="pip-detail-header"><i class="fas fa-user-check"></i><span class="title">Supervisor Notes & Expectations</span></div><div class="pip-detail-body"><div class="pip-content-box">{!! nl2br(e($appraisal->pip_supervisor_notes)) !!}</div></div></div>
    @endif

    <!-- ========== DUAL SIGNATURE SECTION WITHIN PIP ONLY ========== -->
<div class="row mt-4 pt-3 border-top g-3">
    <!-- EMPLOYEE SIGNATURE COLUMN -->
    <div class="col-md-6">
        <div class="pip-signature-card">
            <div class="pip-signature-header">
                <div><i class="fas fa-user-circle"></i><span class="title ms-2">Employee Acknowledgment</span></div>
                <span class="signature-status {{ $pipEmployeeSigned ? 'signature-status-signed' : 'signature-status-pending' }}">
                    <i class="fas {{ $pipEmployeeSigned ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>{{ $pipEmployeeSigned ? 'Signed' : 'Pending' }}
                </span>
            </div>
            <div class="pip-signature-body">
                @if(!$pipEmployeeSigned)
                    <div class="signature-pad-container">
                        <canvas id="pipEmployeeSignatureCanvas" class="signature-canvas" width="500" height="150" style="width:100%; height:150px;"></canvas>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearPIPSignature('employee')"><i class="fas fa-eraser me-1"></i>Clear</button>
                            <button type="button" class="btn btn-sm btn-submit-signature" onclick="submitPIPSignature('employee')"><i class="fas fa-save me-1"></i>Sign PIP</button>
                        </div>
                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle"></i> I acknowledge the PIP terms and commit to improvement.</small>
                    </div>
                @else
                    <div class="text-center py-2">
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <p class="fw-bold mb-0 text-success">Electronically Signed by Employee</p>
                        <p class="small text-muted">{{ $pipEmployeeSignedAt ? \Carbon\Carbon::parse($pipEmployeeSignedAt)->format('M d, Y h:i A') : 'Date recorded' }}</p>
                        @if($appraisal->pip_employee_signature_data)
                            <div class="border rounded p-2 mt-2 bg-light">
                                <img src="{{ $appraisal->pip_employee_signature_data }}" style="max-height:60px; max-width:100%;" alt="Signature">
                            </div>
                        @elseif($appraisal->pip_employee_signature_path)
                            <div class="border rounded p-2 mt-2 bg-light">
                                <img src="{{ asset('storage/' . $appraisal->pip_employee_signature_path) }}" style="max-height:60px; max-width:100%;" alt="Signature">
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- SUPERVISOR SIGNATURE COLUMN -->
    <div class="col-md-6">
        <div class="pip-signature-card">
            <div class="pip-signature-header">
                <div><i class="fas fa-user-tie"></i><span class="title ms-2">Supervisor Approval</span></div>
                <span class="signature-status {{ $pipSupervisorSigned ? 'signature-status-signed' : 'signature-status-pending' }}">
                    <i class="fas {{ $pipSupervisorSigned ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>{{ $pipSupervisorSigned ? 'Signed' : 'Pending' }}
                </span>
            </div>
            <div class="pip-signature-body">
                @if(!$pipSupervisorSigned && ($isSupervisor || auth()->user()->user_type === 'admin'))
                    <div class="signature-pad-container">
                        <canvas id="pipSupervisorSignatureCanvas" class="signature-canvas" width="500" height="150" style="width:100%; height:150px;"></canvas>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearPIPSignature('supervisor')"><i class="fas fa-eraser me-1"></i>Clear</button>
                            <button type="button" class="btn btn-sm btn-submit-signature" onclick="submitPIPSignature('supervisor')"><i class="fas fa-save me-1"></i>Approve & Sign PIP</button>
                        </div>
                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle"></i> Authorized supervisor signature confirms the PIP plan.</small>
                    </div>
                @elseif($pipSupervisorSigned)
                    <div class="text-center py-2">
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <p class="fw-bold mb-0 text-success">Signed by Supervisor</p>
                        <p class="small text-muted">{{ $pipSupervisorSignedAt ? \Carbon\Carbon::parse($pipSupervisorSignedAt)->format('M d, Y h:i A') : 'Date recorded' }}</p>
                        @if($appraisal->pip_supervisor_signature_data)
                            <div class="border rounded p-2 mt-2 bg-light">
                                <img src="{{ $appraisal->pip_supervisor_signature_data }}" style="max-height:60px; max-width:100%;" alt="Signature">
                            </div>
                        @elseif($appraisal->pip_supervisor_signature_path)
                            <div class="border rounded p-2 mt-2 bg-light">
                                <img src="{{ asset('storage/' . $appraisal->pip_supervisor_signature_path) }}" style="max-height:60px; max-width:100%;" alt="Signature">
                            </div>
                        @endif
                    </div>
                @elseif(!$isSupervisor && !$pipSupervisorSigned)
                    <div class="alert alert-secondary text-center py-3 mb-0"><i class="fas fa-lock me-2"></i>Awaiting supervisor signature on PIP.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="alert alert-light border small mt-3 no-print">
    <i class="fas fa-gavel me-2 moic-accent"></i> By signing this Performance Improvement Plan, both parties acknowledge the action items and review schedule. Electronic signatures are legally binding.
</div>
  
    <!-- Follow-up Comments Section -->
    <div class="followup-section mt-4">
        <h6 class="fw-bold mb-3"><i class="fas fa-comment-dots me-2 moic-accent"></i>Follow-up Comments</h6>
        <div class="mb-3"><label class="form-label fw-medium">Add your follow-up comment:</label><textarea id="followupComments" rows="4" class="form-control" placeholder="Add follow-up comments, progress notes, or additional feedback..."></textarea></div>
        <div class="d-flex justify-content-end"><button type="button" class="btn btn-submit-followup" id="submitFollowupBtn"><i class="fas fa-paper-plane me-2"></i>Submit Follow-up Comment</button></div>
        <div class="mt-4"><h6 class="fw-bold mb-2"><i class="fas fa-history me-2"></i>Previous Follow-ups</h6><div id="followupHistory" class="mt-2"><div class="text-muted small fst-italic">No follow-up comments recorded yet.</div></div></div>
    </div>

    <!-- Footer Meta -->
    <div class="pip-footer-meta mt-3"><div><i class="fas fa-flag-checked me-1"></i> Initiated on {{ \Carbon\Carbon::parse($appraisal->pip_initiated_at)->format('F d, Y') }} @if($appraisal->pip_initiated_by) by {{ $appraisal->pipInitiator?->name ?? 'Supervisor' }} @endif</div><div><span class="pip-badge-active"><i class="fas fa-chart-line"></i> Active PIP</span></div></div>
</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for PIP signature submission -->
<form id="pipSignatureForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="signature_data" id="pipSignatureData">
    <input type="hidden" name="signature_role" id="pipSignatureRole">
    <input type="hidden" name="appraisal_id" value="{{ $appraisal->id }}">
</form>

    <!-- Modals for rating (existing) -->
    @if($isAssignedSupervisor && $appraisal->status === 'submitted')
    <!-- KPA Rating Modal -->
    <div class="modal fade" id="kpaRatingModal" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content modal-moic">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKpaTitle">Rate KPA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3" id="modalKpaDescription"></p>
                    
                    <form id="kpaRatingForm">
                        @csrf
                        <input type="hidden" id="kpaId" name="kpa_id">
                        <input type="hidden" id="selfRating" name="self_rating" value="0">
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Your Rating (1-<span id="kpiScale">4</span>)</label>
                            <div class="d-flex gap-2 flex-wrap justify-content-center" id="ratingButtons">
                                <!-- Rating buttons will be dynamically generated -->
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Your Comments</label>
                            <textarea name="supervisor_comments" id="ratingComments" rows="3" class="form-control" 
                                      placeholder="Provide specific feedback..."></textarea>
                            <small class="text-muted" id="commentsHelpText">Required unless you agree with self-rating</small>
                        </div>
                        
                        @if(!(isset($hasMultipleSupervisors) && $hasMultipleSupervisors))
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agree_with_self_rating" id="agreeWithSelfRating" value="1">
                                <label class="form-check-label small" for="agreeWithSelfRating">
                                    Agree with employee's self-rating (<span id="selfRatingDisplay">0</span>)
                                </label>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="submitKPArating(event)">
                        <i class="fas fa-save me-2"></i>Save Rating
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View All Ratings Modal -->
    <div class="modal fade" id="viewRatingsModal" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-purple-600 text-white">
                    <h5 class="modal-title">All Supervisor Ratings</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ratingsContent">
                    <!-- Ratings will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white">Quick Rate Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ratingForm">
                        @csrf
                        <input type="hidden" id="rateEmployeeNumber" name="employee_number">
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="quality">Quality of Work</option>
                                <option value="productivity">Productivity</option>
                                <option value="teamwork">Teamwork</option>
                                <option value="initiative">Initiative</option>
                                <option value="communication">Communication</option>
                                <option value="reliability">Reliability</option>
                                <option value="problem_solving">Problem Solving</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Rating (1-5)</label>
                            <div class="d-flex gap-2 justify-content-between">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="flex-grow-1 text-center">
                                    <input type="radio" name="rating" value="{{ $i }}" class="d-none">
                                    <span class="rating-option d-block">{{ $i }}</span>
                                </label>
                                @endfor
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Comments</label>
                            <textarea name="comments" rows="2" class="form-control" 
                                      placeholder="Provide detailed feedback..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="submitQuickRating(event)">
                        <i class="fas fa-save me-2"></i>Submit Rating
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Return for Revision Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-orange-600 text-white" style="background: #f97316;">
                    <h5 class="modal-title">Return Appraisal for Revision</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('appraisals.return', $appraisal->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Feedback / Reason for Return</label>
                            <textarea name="feedback" rows="3" class="form-control" 
                                      placeholder="Explain what needs to be revised..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style="background: #f97316; color: white;">
                            <i class="fas fa-undo me-2"></i>Return for Revision
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
// ==============================================
// FINAL OPTIMIZED REPORT - Perfect Table Positioning, No Word Cutting
// ==============================================

async function downloadReport() {
    const downloadBtn = document.getElementById('downloadReportBtn');
    const originalText = downloadBtn.innerHTML;
    
    try {
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating Report...';
        downloadBtn.disabled = true;
        downloadBtn.classList.add('btn-download-loading');
        
        // Fetch follow-up comments
        const appraisalId = '{{ $appraisal->id }}';
        let followupsData = [];
        try {
            const response = await fetch(`/appraisals/${appraisalId}/followups`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success && data.followups) {
                followupsData = data.followups;
            }
        } catch (e) {
            console.log('No follow-ups found');
        }
        
        // Create report container with proper width and overflow handling
        const comprehensiveReport = document.createElement('div');
        comprehensiveReport.style.padding = '15px';
        comprehensiveReport.style.fontFamily = 'Arial, Helvetica, sans-serif';
        comprehensiveReport.style.backgroundColor = 'white';
        comprehensiveReport.style.lineHeight = '1.4';
        comprehensiveReport.style.maxWidth = '100%';
        comprehensiveReport.style.margin = '0 auto';
        comprehensiveReport.style.overflow = 'visible';
        comprehensiveReport.style.wordWrap = 'break-word';
        comprehensiveReport.style.wordBreak = 'break-word';
        comprehensiveReport.style.boxSizing = 'border-box';
        
        // Calculate values
        const totalScore = {{ $totalScore ?? 0 }};
        const totalSelfScore = {{ $totalSelfScore ?? 0 }};
        const totalWeight = {{ $totalWeight ?? 100 }};
        const performanceLevel = totalScore >= 90 ? 'Excellent' : (totalScore >= 70 ? 'Good' : (totalScore >= 50 ? 'Fair' : 'Needs Improvement'));
        const performanceColor = totalScore >= 90 ? '#059669' : (totalScore >= 70 ? '#2563eb' : (totalScore >= 50 ? '#d97706' : '#dc2626'));
        const kpaCount = {{ $appraisal->kpas->count() }};
        
        // Calculate font sizes based on KPA count - REDUCED SIZES FOR BETTER FIT
        let kpaFontSize, headerFontSize, tablePadding;
        if (kpaCount <= 4) {
            kpaFontSize = '9px';
            headerFontSize = '10px';
            tablePadding = '6px';
        } else if (kpaCount <= 6) {
            kpaFontSize = '8.5px';
            headerFontSize = '9.5px';
            tablePadding = '5px';
        } else {
            kpaFontSize = '8px';
            headerFontSize = '9px';
            tablePadding = '4px';
        }
        
        // ============ ADD GLOBAL STYLES FIRST ============
        const style = document.createElement('style');
        style.textContent = `
            /* Global PDF/Print Styles - Prevent word cutting */
            * {
                box-sizing: border-box;
                word-wrap: break-word !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                white-space: normal !important;
            }
            
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, Helvetica, sans-serif;
            }
            
            /* Ensure all containers handle overflow properly */
            div, p, span, td, th, li {
                word-wrap: break-word !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
            }
            
            /* Table specific fixes */
            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
                word-wrap: break-word;
                word-break: break-word;
            }
            
            td, th {
                word-wrap: break-word !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                white-space: normal !important;
            }
            
            /* Print media specific rules */
            @media print {
                body { 
                    margin: 0; 
                    padding: 0; 
                    width: 100%;
                }
                
                /* Allow natural page breaks but keep content together where appropriate */
                h1, h2, h3, h4, h5, h6 {
                    page-break-after: avoid;
                    break-after: avoid;
                }
                
                table { 
                    page-break-inside: auto;
                    break-inside: auto;
                }
                
                tr { 
                    page-break-inside: avoid; 
                    break-inside: avoid;
                    page-break-after: auto;
                    break-after: auto;
                }
                
                thead {
                    display: table-header-group;
                }
                
                tfoot {
                    display: table-footer-group;
                }
                
                /* Prevent orphaned content */
                p, div:not(.no-break-inside) {
                    orphans: 3;
                    widows: 3;
                }
                
                /* Allow page breaks within long sections */
                .allow-break {
                    page-break-inside: auto;
                    break-inside: auto;
                }
                
                /* Keep sections together when possible */
                .keep-together {
                    page-break-inside: avoid;
                    break-inside: avoid;
                }
            }
            
            /* Specific styles for PDF generation */
            .report-section {
                margin-bottom: 20px;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            
            .allow-page-break {
                page-break-inside: auto;
                break-inside: auto;
            }
            
            /* Card styles */
            .summary-card {
                background: #f8fafc;
                padding: 12px 8px;
                border-radius: 8px;
                text-align: center;
                border: 1px solid #e2e8f0;
            }
            
            .summary-card-value {
                font-size: 24px;
                font-weight: bold;
            }
            
            .summary-card-label {
                font-size: 10px;
                color: #475569;
                text-transform: uppercase;
            }
            
            /* KPA Table styles */
            .kpa-table {
                width: 100%;
                border-collapse: collapse;
                font-size: ${kpaFontSize};
                table-layout: fixed;
            }
            
            .kpa-table th {
                background: #110484;
                color: white;
                border: 1px solid #999;
                padding: ${tablePadding} 4px;
                font-size: ${headerFontSize};
                text-align: center;
                font-weight: 600;
                word-wrap: break-word;
            }
            
            .kpa-table td {
                border: 1px solid #999;
                padding: ${tablePadding} 4px;
                vertical-align: top;
                word-wrap: break-word;
            }
            
            /* Column widths for KPA table */
            .col-category { width: 10%; }
            .col-kpa { width: 24%; }
            .col-weight { width: 6%; }
            .col-self { width: 7%; }
            .col-sup { width: 7%; }
            .col-score { width: 7%; }
            .col-emp-comment { width: 19.5%; }
            .col-sup-comment { width: 19.5%; }
        `;
        comprehensiveReport.appendChild(style);
        
        // ============ SECTION 1: HEADER ============
        const headerSection = document.createElement('div');
        headerSection.className = 'report-section keep-together';
        headerSection.innerHTML = `
            <div style="text-align: center; padding: 15px; background: linear-gradient(135deg, #110484, #1a0c9e); color: white; border-radius: 8px;">
                <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Performance Appraisal Report</h1>
                <p style="margin: 8px 0 0 0; font-size: 11px;">Generated: {{ $reportDate }} | Report ID: #{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p style="margin: 6px 0 0 0; font-size: 14px;"><strong>{{ $appraisal->user->name ?? $appraisal->employee_name }}</strong> | {{ $appraisal->employee_number }}</p>
                <p style="margin: 4px 0 0 0; font-size: 11px;">{{ $appraisal->department ?? 'Not specified' }} | {{ $appraisal->job_title ?? $appraisal->position ?? 'Not specified' }}</p>
                <p style="margin: 4px 0 0 0; font-size: 10px;">Period: {{ $appraisal->period }} ({{ \Carbon\Carbon::parse($appraisal->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($appraisal->end_date)->format('M d, Y') }}) | Status: {{ ucfirst($appraisal->status) }}</p>
            </div>
        `;
        comprehensiveReport.appendChild(headerSection);
        
        // ============ SECTION 2: PERFORMANCE SUMMARY ============
        const summarySection = document.createElement('div');
        summarySection.className = 'report-section keep-together';
        summarySection.innerHTML = `
            <h2 style="background: #110484; color: white; padding: 8px 12px; border-radius: 5px; margin: 0 0 12px 0; font-size: 16px;">Performance Summary</h2>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 15px;">
                <div class="summary-card">
                    <div class="summary-card-label">Total Weight</div>
                    <div class="summary-card-value" style="color: #110484;">${totalWeight}%</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-label">Self Score</div>
                    <div class="summary-card-value" style="color: #2563eb;">${totalSelfScore.toFixed(1)}%</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-label">Final Score</div>
                    <div class="summary-card-value" style="color: ${performanceColor};">${totalScore.toFixed(1)}%</div>
                    <div style="font-size: 11px; color: ${performanceColor};">${performanceLevel}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-card-label">PIP Status</div>
                    <div class="summary-card-value" style="color: {{ $pipActive ? '#e7581c' : '#10b981' }}; font-size: 20px;">
                        {{ $pipActive ? 'Active' : 'Not Required' }}
                    </div>
                    @if($pipActive && $appraisal->pip_end_date)
                    <div style="font-size: 9px;">Until {{ \Carbon\Carbon::parse($appraisal->pip_end_date)->format('M d, Y') }}</div>
                    @endif
                </div>
            </div>
            <div style="background: #f1f5f9; padding: 12px; border-radius: 6px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 12px;">
                    <span>Overall Performance Progress</span>
                    <span><strong>${totalScore.toFixed(1)}%</strong></span>
                </div>
                <div style="background: #e2e8f0; height: 12px; border-radius: 6px; overflow: hidden;">
                    <div style="width: ${Math.min(totalScore, 100)}%; height: 100%; background: linear-gradient(90deg, #110484, #e7581c);"></div>
                </div>
            </div>
        `;
        comprehensiveReport.appendChild(summarySection);
        
        // ============ SECTION 3: KPAs TABLE with PROPER POSITIONING ============
        const kpaSection = document.createElement('div');
        kpaSection.className = 'report-section';
        kpaSection.style.marginBottom = '20px';
        kpaSection.style.width = '100%';
        kpaSection.style.overflowX = 'visible';
        
        let kpaTableHtml = `
            <h2 style="background: #110484; color: white; padding: 8px 12px; border-radius: 5px; margin: 0 0 12px 0; font-size: 16px;">Key Performance Areas (KPAs)</h2>
            <div style="width: 100%; overflow-x: visible;">
                <table class="kpa-table">
                    <colgroup>
                        <col class="col-category">
                        <col class="col-kpa">
                        <col class="col-weight">
                        <col class="col-self">
                        <col class="col-sup">
                        <col class="col-score">
                        <col class="col-emp-comment">
                        <col class="col-sup-comment">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>KPA / Indicators</th>
                            <th>Wt%</th>
                            <th>Self<br>Rating</th>
                            <th>Sup<br>Rating</th>
                            <th>Score</th>
                            <th>Employee<br>Comments</th>
                            <th>Supervisor<br>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        @foreach($appraisal->kpas as $kpa)
        @php
            $kpi = $kpa->kpi ?: 4;
            $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
            $individualScore = ($finalRating / $kpi) * $kpa->weight;
            $scoreColor = $individualScore >= ($kpa->weight * 0.9) ? '#059669' : ($individualScore >= ($kpa->weight * 0.7) ? '#2563eb' : ($individualScore >= ($kpa->weight * 0.5) ? '#d97706' : '#dc2626'));
            $employeeComment = $kpa->comments ?? '';
            $supervisorComment = $kpa->supervisor_comments ?? '';
        @endphp
        kpaTableHtml += `
            <tr>
                <td style="background: #f9fafb;"><strong>{{ $kpa->category }}</strong></td>
                <td>
                    <strong>{{ $kpa->kpa }}</strong>
                    @if($kpa->result_indicators)
                    <div style="font-size: 8.5px; color: #555; margin-top: 3px;">📊 {{ $kpa->result_indicators }}</div>
                    @endif
                    @if($kpa->target || $kpa->actual_achievement)
                    <div style="font-size: 8px; color: #777; margin-top: 2px;">🎯 {{ $kpa->target ?? 'N/A' }} | ✅ {{ $kpa->actual_achievement ?? 'N/A' }}</div>
                    @endif
                </td>
                <td style="text-align: center; vertical-align: middle;">{{ $kpa->weight }}%</td>
                <td style="text-align: center; vertical-align: middle;">{{ $kpa->self_rating }}/{{ $kpi }}</td>
                <td style="text-align: center; vertical-align: middle;">{{ $kpa->supervisor_rating ?? '—' }}/{{ $kpi }}</td>
                <td style="text-align: center; vertical-align: middle; font-weight: bold; color: {{ $scoreColor }};">{{ number_format($individualScore, 1) }}%</td>
                <td style="background: #eff6ff;">
                    @if(!empty($employeeComment))
                    <span style="color: #1e40af;">📝</span> {{ $employeeComment }}
                    @else
                    <span style="color: #999;">—</span>
                    @endif
                </td>
                <td style="background: #ecfdf5;">
                    @if(!empty($supervisorComment))
                    <span style="color: #10b981;">👔</span> {{ $supervisorComment }}
                    @else
                    <span style="color: #999;">—</span>
                    @endif
                </td>
            </tr>
        `;
        @endforeach
        
        kpaTableHtml += `
                    </tbody>
                    <tfoot>
                        <tr style="background: #f3f4f6;">
                            <td colspan="7" style="border: 1px solid #999; padding: 8px; text-align: right; font-weight: bold; font-size: 13px;">TOTAL SCORE:</td>
                            <td style="border: 1px solid #999; padding: 8px; text-align: center; font-weight: bold; font-size: 14px; color: {{ $totalScore < 75 ? '#dc2626' : '#10b981' }};">{{ number_format($totalScore, 1) }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;
        kpaSection.innerHTML = kpaTableHtml;
        comprehensiveReport.appendChild(kpaSection);
        
        // ============ SECTION 4: ADDITIONAL COMMENTS ============
        const additionalSection = document.createElement('div');
        additionalSection.className = 'report-section keep-together';
        additionalSection.innerHTML = `
            <h2 style="background: #110484; color: white; padding: 8px 12px; border-radius: 5px; margin: 0 0 12px 0; font-size: 16px;">Additional Comments & Feedback</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <h3 style="color: #1e40af; margin: 0 0 8px 0; font-size: 13px;">📝 Employee Reflections</h3>
                    <div style="margin-bottom: 12px;">
                        <strong style="font-size: 11px;">Development Needs:</strong>
                        <div style="background: white; padding: 8px; border-radius: 4px; margin-top: 4px; font-size: 10px; line-height: 1.4;">
                            {{ $appraisal->development_needs ?: 'No development needs recorded.' }}
                        </div>
                    </div>
                    <div>
                        <strong style="font-size: 11px;">Additional Comments:</strong>
                        <div style="background: white; padding: 8px; border-radius: 4px; margin-top: 4px; font-size: 10px; line-height: 1.4;">
                            {{ $appraisal->employee_comments ?: 'No additional comments.' }}
                        </div>
                    </div>
                </div>
                <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <h3 style="color: #10b981; margin: 0 0 8px 0; font-size: 13px;">👔 Supervisor Assessment</h3>
                    <div>
                        <strong style="font-size: 11px;">Feedback & Evaluation:</strong>
                        <div style="background: white; padding: 8px; border-radius: 4px; margin-top: 4px; font-size: 10px; line-height: 1.4;">
                            {{ $appraisal->supervisor_comments ?: 'No supervisor feedback recorded.' }}
                        </div>
                    </div>
                    @if($appraisal->approved_at)
                    <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #666;">
                        <div><strong>Approved By:</strong> {{ $appraisal->approved_by ?? 'Supervisor' }}</div>
                        <div><strong>Approved On:</strong> {{ \Carbon\Carbon::parse($appraisal->approved_at)->format('M d, Y h:i A') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        `;
        comprehensiveReport.appendChild(additionalSection);
        
        // ============ SECTION 5: PIP INFORMATION (if active) ============
        @if($pipActive)
        const pipSection = document.createElement('div');
        pipSection.className = 'report-section';
        pipSection.style.pageBreakBefore = 'auto';
        pipSection.innerHTML = `
            <h2 style="background: #e7581c; color: white; padding: 8px 12px; border-radius: 5px; margin: 0 0 12px 0; font-size: 16px;">📋 Performance Improvement Plan (PIP)</h2>
            
            <div style="background: #fefaf5; border: 1px solid #f0e2d6; border-radius: 6px; margin-bottom: 15px; overflow: hidden;">
                <div style="background: #f0e2d6; padding: 8px 12px; border-bottom: 1px solid #e2d5c8;">
                    <strong style="font-size: 12px; color: #4b2e1a;">Toll Plaza Operations : Supplementary Information File, Ref PM018 | Rev 0, Aug 2024</strong>
                </div>
                <div style="padding: 15px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Employee Name</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 12px;">{{ $appraisal->user->name ?? $appraisal->employee_name ?? 'System Employee' }}</div>
                        </div>
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Date</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 12px;">{{ now()->format('F d, Y') }}</div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Job Title</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 11px;">{{ $appraisal->job_title ?? $appraisal->position ?? 'Staff' }}</div>
                        </div>
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Department</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 11px;">{{ $appraisal->department ?? 'Operations' }}</div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Manager Name</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 11px;">{{ auth()->user()->name ?? 'Supervisor' }}</div>
                        </div>
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Hired Date</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 11px;">{{ $appraisal->hired_date ?? ($appraisal->start_date ?? 'N/A') }}</div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Area of Concern</label>
                        <div style="background: #fff8f0; padding: 8px; border-radius: 4px; font-size: 10px; border: 1px solid #f0e2d6;">
                            Performance score <strong>{{ number_format($totalScore, 1) }}%</strong> below 75% threshold. Specific KPAs require immediate improvement.
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Goal or Plan for Improvement</label>
                        <div style="background: #fff8f0; padding: 8px; border-radius: 4px; font-size: 10px; border: 1px solid #f0e2d6;">
                            Achieve minimum 85% on all core KPIs by end of PIP period.
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Plan Start Date</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 11px;">{{ \Carbon\Carbon::parse($appraisal->pip_start_date)->format('F d, Y') }}</div>
                        </div>
                        <div>
                            <label style="font-size: 10px; font-weight: bold; color: #9b6a4b; display: block;">Plan End Date</label>
                            <div style="border-bottom: 1px solid #ddd; padding: 3px 0; font-size: 11px;">{{ \Carbon\Carbon::parse($appraisal->pip_end_date)->format('F d, Y') }}</div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="font-size: 11px; font-weight: bold; color: #4b2e1a; display: block; margin-bottom: 8px;">Action Plan & Objectives</label>
                        <table style="width: 100%; border-collapse: collapse; font-size: 9.5px; table-layout: fixed;">
                            <thead>
                                <tr style="background: #f0e2d6;">
                                    <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 30%;">Action</th>
                                    <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 35%;">Objective</th>
                                    <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 35%;">Measurement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 6px;">Submit weekly progress report every Friday</td>
                                    <td style="border: 1px solid #ddd; padding: 6px;">Track improvements and identify blockers</td>
                                    <td style="border: 1px solid #ddd; padding: 6px;">Completion rate / quality audit</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 6px;">Complete mandatory e-learning modules</td>
                                    <td style="border: 1px solid #ddd; padding: 6px;">Skill enhancement and process knowledge</td>
                                    <td style="border: 1px solid #ddd; padding: 6px;">Certification achieved</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 6px;"></td>
                                    <td style="border: 1px solid #ddd; padding: 6px;"></td>
                                    <td style="border: 1px solid #ddd; padding: 6px;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div style="background: #fefaf5; border: 1px solid #f0e2d6; border-radius: 6px; margin-bottom: 15px;">
                <div style="background: #fffaf5; padding: 8px 12px; border-bottom: 1px solid #f3e9e0;">
                    <strong style="font-size: 12px; color: #4b2e1a;">Plan Timeline</strong>
                </div>
                <div style="padding: 15px;">
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        <div style="background: #fefaf5; border-radius: 8px; padding: 8px; border: 1px solid #f3e7de;">
                            <div style="font-size: 9px; text-transform: uppercase; color: #9b6a4b;">Start Date</div>
                            <div style="font-size: 12px; font-weight: 600;">{{ \Carbon\Carbon::parse($appraisal->pip_start_date)->format('M d, Y') }}</div>
                        </div>
                        <div style="background: #fefaf5; border-radius: 8px; padding: 8px; border: 1px solid #f3e7de;">
                            <div style="font-size: 9px; text-transform: uppercase; color: #9b6a4b;">End Date</div>
                            <div style="font-size: 12px; font-weight: 600;">{{ \Carbon\Carbon::parse($appraisal->pip_end_date)->format('M d, Y') }}</div>
                        </div>
                        <div style="background: #fefaf5; border-radius: 8px; padding: 8px; border: 1px solid #f3e7de;">
                            <div style="font-size: 9px; text-transform: uppercase; color: #9b6a4b;">Duration</div>
                            <div style="font-size: 12px; font-weight: 600;">{{ \Carbon\Carbon::parse($appraisal->pip_start_date)->diffInDays(\Carbon\Carbon::parse($appraisal->pip_end_date)) }} days</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="background: #fefaf5; border: 1px solid #f0e2d6; border-radius: 6px; margin-bottom: 15px;">
                <div style="background: #fffaf5; padding: 8px 12px; border-bottom: 1px solid #f3e9e0;">
                    <strong style="font-size: 12px; color: #4b2e1a;">Improvement Plan & Action Items</strong>
                </div>
                <div style="padding: 15px;">
                    <div style="background: #fefcf8; border-radius: 8px; padding: 12px; border: 1px solid #f5eee7; line-height: 1.5; font-size: 10px;">
                        {{ $appraisal->pip_plan ?: 'No plan details available.' }}
                    </div>
                </div>
            </div>
            
            @if($appraisal->pip_supervisor_notes)
            <div style="background: #fefaf5; border: 1px solid #f0e2d6; border-radius: 6px; margin-bottom: 15px;">
                <div style="background: #fffaf5; padding: 8px 12px; border-bottom: 1px solid #f3e9e0;">
                    <strong style="font-size: 12px; color: #4b2e1a;">Supervisor Notes & Expectations</strong>
                </div>
                <div style="padding: 15px;">
                    <div style="background: #fefcf8; border-radius: 8px; padding: 12px; border: 1px solid #f5eee7; line-height: 1.5; font-size: 10px;">
                        {{ $appraisal->pip_supervisor_notes }}
                    </div>
                </div>
            </div>
            @endif
            
            <div style="background: #fefaf5; border: 1px solid #f0e2d6; border-radius: 6px; margin-top: 15px;">
                <div style="background: #fffaf5; padding: 8px 12px; border-bottom: 1px solid #f3e9e0;">
                    <strong style="font-size: 12px; color: #4b2e1a;">Signatures</strong>
                </div>
                <div style="padding: 15px;">
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                        <div>
                            <div style="font-size: 10px; font-weight: bold; color: #9b6a4b;">Employee Signature</div>
                            <div style="border-bottom: 1px solid #ddd; margin-top: 6px; padding-bottom: 3px;">&nbsp;</div>
                            <div style="font-size: 8px; color: #999; margin-top: 3px;">Will be collected upon submission</div>
                        </div>
                        <div>
                            <div style="font-size: 10px; font-weight: bold; color: #9b6a4b;">Manager Signature</div>
                            <div style="border-bottom: 1px solid #ddd; margin-top: 6px; padding-bottom: 3px;">{{ auth()->user()->name ?? 'Supervisor' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 10px; font-weight: bold; color: #9b6a4b;">Date</div>
                            <div style="border-bottom: 1px solid #ddd; margin-top: 6px; padding-bottom: 3px;">{{ now()->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 10px; border-top: 1px solid #f0e2d6;">
                <div style="font-size: 9px; color: #9b7a62;">
                    Initiated on {{ \Carbon\Carbon::parse($appraisal->pip_initiated_at)->format('F d, Y') }}
                    @if($appraisal->pip_initiated_by)
                        by {{ $appraisal->pipInitiator?->name ?? 'Supervisor' }}
                    @endif
                </div>
                <div style="background: #ffedd5; color: #9a3412; border-radius: 20px; padding: 3px 10px; font-size: 9px; font-weight: 600;">
                    Active PIP
                </div>
            </div>
        `;
        comprehensiveReport.appendChild(pipSection);
        @endif
        
        // ============ SECTION 6: FOLLOW-UP COMMENTS ============
        if (followupsData.length > 0) {
            const followupSection = document.createElement('div');
            followupSection.className = 'report-section';
            
            let followupsHtml = `
                <h2 style="background: #110484; color: white; padding: 8px 12px; border-radius: 5px; margin: 0 0 12px 0; font-size: 16px;">💬 PIP Follow-up Comments (${followupsData.length})</h2>
                <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
            `;
            followupsData.forEach((f, index) => {
                followupsHtml += `
                    <div style="border-bottom: ${index === followupsData.length - 1 ? 'none' : '1px solid #e2e8f0'}; padding: 10px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <strong style="font-size: 11px; color: #110484;">👤 ${escapeHtml(f.author)}</strong>
                            <small style="font-size: 9px; color: #666;">${new Date(f.created_at).toLocaleString()}</small>
                        </div>
                        <div style="font-size: 10px; color: #333; line-height: 1.4; background: white; padding: 8px; border-radius: 4px;">
                            ${escapeHtml(f.comment).replace(/\n/g, '<br>')}
                        </div>
                    </div>
                `;
            });
            followupsHtml += `</div>`;
            followupSection.innerHTML = followupsHtml;
            comprehensiveReport.appendChild(followupSection);
        }
        
        // ============ FOOTER ============
        const footer = document.createElement('div');
        footer.style.marginTop = '15px';
        footer.style.paddingTop = '10px';
        footer.style.borderTop = '1px solid #e2e8f0';
        footer.style.textAlign = 'center';
        footer.style.color = '#666';
        footer.style.fontSize = '9px';
        footer.innerHTML = `
            <p style="margin: 0;">MOIC Performance Appraisal System © {{ date('Y') }} | Report ID: #{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p style="margin: 3px 0 0 0; font-size: 8px;">This is an official system-generated report.</p>
        `;
        comprehensiveReport.appendChild(footer);
        
        // Configure html2pdf options
        const opt = {
            margin: [0.3, 0.3, 0.3, 0.3], // Reduced margins for more content space
            filename: `Appraisal_Report_#{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}_{{ $appraisal->employee_name ?? $appraisal->user->name ?? 'Employee' }}.pdf`,
            image: { type: 'jpeg', quality: 0.95 },
            html2canvas: { 
                scale: 2,
                letterRendering: true,
                useCORS: true,
                logging: false,
                windowWidth: 1200,
                allowTaint: false
            },
            jsPDF: { 
                unit: 'in', 
                format: 'a4', 
                orientation: 'landscape',
                compress: true
            },
            pagebreak: { 
                mode: ['css', 'legacy'],
                before: '.page-break-before',
                after: '.page-break-after',
                avoid: ['tr', 'td', 'th', '.keep-together']
            }
        };
        
        await html2pdf().set(opt).from(comprehensiveReport).save();
        showMessage('Report downloaded successfully!', 'success');
        
    } catch (error) {
        console.error('PDF generation error:', error);
        showMessage('Error generating report. Please try again.', 'error');
    } finally {
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
        downloadBtn.classList.remove('btn-download-loading');
    }
}
        // ==============================================
        // PIP FUNCTIONS
        // ==============================================

        let pipModal = null;
        let pipDetailsModal = null;

        function initiatePIP() {
            if (!pipModal) {
                pipModal = new bootstrap.Modal(document.getElementById('pipModal'));
            }
            
            const form = document.getElementById('pipForm');
            if (form) {
                form.reset();
                const today = new Date().toISOString().split('T')[0];
                const ninetyDaysLater = new Date();
                ninetyDaysLater.setDate(ninetyDaysLater.getDate() + 90);
                const endDate = ninetyDaysLater.toISOString().split('T')[0];
                
                const startDateInput = document.querySelector('input[name="pip_start_date"]');
                const endDateInput = document.querySelector('input[name="pip_end_date"]');
                if (startDateInput) startDateInput.value = today;
                if (endDateInput) endDateInput.value = endDate;
            }
            
            pipModal.show();
        }

        function viewPIPDetails() {
            if (!pipDetailsModal) {
                pipDetailsModal = new bootstrap.Modal(document.getElementById('pipDetailsModal'));
            }
            pipDetailsModal.show();
        }

        // ==============================================
        // PIP FOLLOW-UP FUNCTIONS - PERSISTENT STORAGE
        // ==============================================

        async function loadFollowupHistory() {
            const historyContainer = document.getElementById('followupHistory');
            if (!historyContainer) return;
            
            const appraisalId = '{{ $appraisal->id }}';
            const endpoint = `/appraisals/${appraisalId}/followups`;
            
            try {
                historyContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-muted"></div><p class="small text-muted mt-2">Loading comments...</p></div>';
                
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load');
                }
                
                const data = await response.json();
                
                if (data.success && data.followups && data.followups.length > 0) {
                    let html = '';
                    data.followups.forEach(f => {
                        const authorBadge = f.author_type === 'supervisor' 
                            ? '<span class="badge bg-warning text-dark ms-2" style="font-size: 0.6rem;">Supervisor</span>'
                            : (f.author_type === 'hr' ? '<span class="badge bg-info text-dark ms-2" style="font-size: 0.6rem;">HR</span>' : '');
                        
                        html += `
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                    <div class="d-flex align-items-center">
                                        <strong class="small">${escapeHtml(f.author)}</strong>
                                        ${authorBadge}
                                    </div>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>${escapeHtml(f.formatted_date || new Date(f.created_at).toLocaleString())}
                                    </small>
                                </div>
                                <div class="mt-2 small" style="color: #2c1c10; line-height: 1.5; white-space: pre-wrap;">
                                    ${escapeHtml(f.comment).replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        `;
                    });
                    historyContainer.innerHTML = html;
                } else {
                    historyContainer.innerHTML = '<div class="text-muted small fst-italic text-center py-3"><i class="fas fa-comment-slash me-2"></i>No follow-up comments recorded yet.</div>';
                }
            } catch (error) {
                console.error('Error loading follow-ups:', error);
                historyContainer.innerHTML = '<div class="text-muted small fst-italic text-center py-3"><i class="fas fa-exclamation-triangle me-2"></i>Unable to load comments. Please refresh and try again.</div>';
            }
        }

        async function submitFollowupComment() {
            const commentTextarea = document.getElementById('followupComments');
            const comment = commentTextarea?.value.trim();
            
            if (!comment) {
                showMessage('Please enter a follow-up comment.', 'warning');
                return;
            }
            
            const submitBtn = document.getElementById('submitFollowupBtn');
            const originalText = submitBtn?.innerHTML;
            
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                submitBtn.disabled = true;
            }
            
            const appraisalId = '{{ $appraisal->id }}';
            const endpoint = `/appraisals/${appraisalId}/followup`;
            
            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ comment: comment })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage('Follow-up comment saved successfully!', 'success');
                    if (commentTextarea) commentTextarea.value = '';
                    await loadFollowupHistory();
                } else {
                    showMessage(data.message || 'Error saving comment. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Error saving follow-up:', error);
                showMessage('Network error. Please check your connection and try again.', 'error');
            } finally {
                if (submitBtn) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }
        }

        // ==============================================
        // COMMENT MODAL FUNCTIONS
        // ==============================================

        let commentModal = null;

        function cleanupModalFocus(modalElement) {
            if (!modalElement) return;
            modalElement.setAttribute('aria-hidden', 'false');
            modalElement.removeAttribute('inert');
            
            const lastFocus = document.querySelector('[data-modal-trigger]');
            if (lastFocus && document.body.contains(lastFocus)) {
                setTimeout(() => {
                    lastFocus.focus();
                    lastFocus.removeAttribute('data-modal-trigger');
                }, 150);
            }
        }

        function showFullCommentModal(title, content, author, date) {
            if (!commentModal) {
                commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            }
            
            const trigger = document.activeElement;
            if (trigger && trigger !== document.body) {
                trigger.setAttribute('data-modal-trigger', 'true');
            }
            
            document.getElementById('commentModalTitle').textContent = title;
            document.getElementById('commentModalAuthor').textContent = author;
            document.getElementById('commentModalDate').textContent = date ? new Date(date).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            }) : 'N/A';
            
            let formattedContent = content || '';
            formattedContent = formattedContent.replace(/\\n/g, '\n').replace(/\\r/g, '').replace(/\\t/g, '    ');
            document.getElementById('commentModalContent').innerHTML = formattedContent.replace(/\n/g, '<br>').replace(/  /g, ' &nbsp;');
            
            const ratingContainer = document.getElementById('commentModalRatingContainer');
            if (ratingContainer) ratingContainer.style.display = 'none';
            
            const modalElement = document.getElementById('commentModal');
            modalElement.removeAttribute('aria-hidden');
            modalElement.removeAttribute('inert');
            commentModal.show();
        }

        function showFullCommentWithRating(title, content, author, date, rating) {
            if (!commentModal) {
                commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            }
            
            const trigger = document.activeElement;
            if (trigger && trigger !== document.body) {
                trigger.setAttribute('data-modal-trigger', 'true');
            }
            
            document.getElementById('commentModalTitle').textContent = title;
            document.getElementById('commentModalAuthor').textContent = author;
            document.getElementById('commentModalDate').textContent = date ? new Date(date).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            }) : 'N/A';
            
            let formattedContent = content || '';
            formattedContent = formattedContent.replace(/\\n/g, '\n').replace(/\\r/g, '').replace(/\\t/g, '    ');
            document.getElementById('commentModalContent').innerHTML = formattedContent.replace(/\n/g, '<br>').replace(/  /g, ' &nbsp;');
            
            const ratingElement = document.getElementById('commentModalRating');
            const ratingContainer = document.getElementById('commentModalRatingContainer');
            if (ratingElement && ratingContainer) {
                ratingElement.textContent = rating;
                ratingContainer.style.display = 'flex';
            }
            
            const modalElement = document.getElementById('commentModal');
            modalElement.removeAttribute('aria-hidden');
            modalElement.removeAttribute('inert');
            commentModal.show();
        }

        // ==============================================
        // KPA RATING MODAL FUNCTIONS
        // ==============================================

        let currentKpaModal = null;

        function showKPARatingModal(kpaId, category, kpa, kpi, selfRating) {
            const trigger = document.activeElement;
            if (trigger && trigger !== document.body) {
                trigger.setAttribute('data-modal-trigger', 'true');
            }
            
            const modal = new bootstrap.Modal(document.getElementById('kpaRatingModal'));
            
            document.getElementById('modalKpaTitle').textContent = `${category}`;
            document.getElementById('modalKpaDescription').textContent = kpa;
            document.getElementById('kpiScale').textContent = kpi;
            document.getElementById('kpaId').value = kpaId;
            document.getElementById('selfRating').value = selfRating;
            
            const selfRatingDisplay = document.getElementById('selfRatingDisplay');
            if (selfRatingDisplay) selfRatingDisplay.textContent = selfRating;
            
            const ratingContainer = document.getElementById('ratingButtons');
            ratingContainer.innerHTML = '';
            
            for(let i = 1; i <= kpi; i++) {
                const label = document.createElement('label');
                label.className = 'flex-grow-1 text-center';
                label.innerHTML = `<input type="radio" name="supervisor_rating" value="${i}" class="d-none"><span class="rating-option d-block" data-value="${i}">${i}</span>`;
                ratingContainer.appendChild(label);
            }
            
            document.querySelectorAll('#kpaRatingModal .rating-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('#kpaRatingModal .rating-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    const radio = this.previousElementSibling;
                    if (radio) radio.checked = true;
                });
            });
            
            document.getElementById('kpaRatingForm').reset();
            const commentsField = document.getElementById('ratingComments');
            const commentsHelpText = document.getElementById('commentsHelpText');
            const agreeCheckbox = document.getElementById('agreeWithSelfRating');
            
            if (commentsField) {
                commentsField.value = '';
                commentsField.removeAttribute('required');
                commentsField.disabled = false;
            }
            if (commentsHelpText) {
                commentsHelpText.style.display = 'block';
                commentsHelpText.innerHTML = 'Required unless you agree with self-rating';
                commentsHelpText.style.color = '#6c757d';
            }
            if (agreeCheckbox) {
                agreeCheckbox.checked = false;
                agreeCheckbox.removeEventListener('change', handleAgreeCheckbox);
                agreeCheckbox.addEventListener('change', handleAgreeCheckbox);
            }
            
            const modalElement = document.getElementById('kpaRatingModal');
            if (modalElement) {
                modalElement.removeAttribute('aria-hidden');
                modalElement.removeAttribute('inert');
            }
            
            currentKpaModal = modal;
            modal.show();
        }

        function handleAgreeCheckbox(event) {
            const checkbox = event.target;
            const ratingOptions = document.querySelectorAll('#kpaRatingModal .rating-option');
            const ratingInputs = document.querySelectorAll('#kpaRatingModal input[name="supervisor_rating"]');
            const commentsField = document.getElementById('ratingComments');
            const commentsHelpText = document.getElementById('commentsHelpText');
            const selfRating = document.getElementById('selfRating').value;
            
            if (checkbox.checked) {
                ratingOptions.forEach(opt => {
                    opt.style.opacity = '0.5';
                    opt.style.pointerEvents = 'none';
                    opt.classList.remove('selected');
                });
                ratingInputs.forEach(input => input.checked = false);
                if (commentsField) {
                    commentsField.value = `I agree with the employee's self-rating of ${selfRating}.`;
                    commentsField.removeAttribute('required');
                    commentsField.disabled = true;
                }
                if (commentsHelpText) {
                    commentsHelpText.innerHTML = `Using employee's self-rating (${selfRating})`;
                    commentsHelpText.style.color = '#10b981';
                }
            } else {
                ratingOptions.forEach(opt => {
                    opt.style.opacity = '1';
                    opt.style.pointerEvents = 'auto';
                });
                if (commentsField) {
                    commentsField.value = '';
                    commentsField.setAttribute('required', 'required');
                    commentsField.disabled = false;
                }
                if (commentsHelpText) {
                    commentsHelpText.innerHTML = 'Required unless you agree with self-rating';
                    commentsHelpText.style.color = '#6c757d';
                }
            }
        }

        function submitKPArating(event) {
            const form = document.getElementById('kpaRatingForm');
            const formData = new FormData(form);
            const kpaId = document.getElementById('kpaId').value;
            const hasMultipleSupervisors = '{{ isset($hasMultipleSupervisors) && $hasMultipleSupervisors ? 'true' : 'false' }}';
            const agreeCheckbox = document.getElementById('agreeWithSelfRating');
            const ratingSelected = document.querySelector('input[name="supervisor_rating"]:checked');
            const commentsField = document.getElementById('ratingComments');
            const selfRating = document.getElementById('selfRating').value;
            
            if (agreeCheckbox && agreeCheckbox.checked) {
                formData.set('supervisor_rating', selfRating);
                if (!formData.get('supervisor_comments') || formData.get('supervisor_comments').trim() === '') {
                    formData.set('supervisor_comments', `I agree with the employee's self-rating of ${selfRating}.`);
                }
            } else {
                if (!ratingSelected) {
                    showMessage('Please select a rating', 'error');
                    return;
                }
                const ratingValue = parseInt(ratingSelected.value);
                if (ratingValue < 1) {
                    showMessage('Rating must be at least 1', 'error');
                    return;
                }
                if (!commentsField || !commentsField.value || commentsField.value.trim() === '') {
                    showMessage('Please provide comments', 'error');
                    commentsField?.focus();
                    return;
                }
            }
            
            const url = hasMultipleSupervisors === 'true' 
                ? '{{ route("kpa.rate-multiple") }}' 
                : '{{ route("kpa.rate") }}';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', csrfToken);
            
            if (hasMultipleSupervisors === 'false') {
                if (agreeCheckbox && !agreeCheckbox.checked) {
                    formData.set('agree_with_self_rating', '0');
                } else if (agreeCheckbox && agreeCheckbox.checked) {
                    formData.set('agree_with_self_rating', '1');
                }
            }
            
            const submitBtn = event.target;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            submitBtn.disabled = true;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message || 'Rating submitted successfully!', 'success');
                    if (currentKpaModal) currentKpaModal.hide();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showMessage(data.message || 'Error submitting rating', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Network error occurred. Please try again.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // ==============================================
        // VIEW ALL RATINGS MODAL
        // ==============================================

        function viewAllRatings(kpaId) {
            const trigger = document.activeElement;
            if (trigger && trigger !== document.body) {
                trigger.setAttribute('data-modal-trigger', 'true');
            }
            
            const modal = new bootstrap.Modal(document.getElementById('viewRatingsModal'));
            const content = document.getElementById('ratingsContent');
            
            content.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-purple-600 mb-2" role="status"></div><p class="text-muted small">Loading ratings...</p></div>`;
            
            fetch(`/appraisals/kpa/${kpaId}/ratings`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '';
                        if (data.ratings && data.ratings.length > 0) {
                            data.ratings.forEach(rating => {
                                const ratingPercentage = (rating.rating / data.kpi) * 100;
                                html += `
                                    <div class="card mb-3 border">
                                        <div class="card-body p-3">
                                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                                <div><h6 class="fw-semibold mb-1">${escapeHtml(rating.supervisor_name || 'Supervisor')}${rating.supervisor_id === '{{ auth()->user()->employee_number }}' ? '<span class="badge bg-purple-100 text-purple-800 ms-2">You</span>' : ''}</h6></div>
                                                <div class="text-end"><span class="h6 fw-bold">${rating.rating}/${data.kpi}</span><small class="d-block text-muted">${ratingPercentage.toFixed(1)}%</small></div>
                                            </div>
                                            ${rating.comments ? `<div class="mt-2 pt-2 border-top"><small class="text-muted d-block mb-1"><strong>Comments:</strong></small><div class="small">${escapeHtml(rating.comments)}</div></div>` : ''}
                                            <small class="text-muted d-block mt-2"><i class="far fa-clock me-1"></i>${new Date(rating.created_at).toLocaleDateString()}</small>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html = '<p class="text-center text-muted py-4">No ratings available.</p>';
                        }
                        content.innerHTML = html;
                    } else {
                        content.innerHTML = `<p class="text-center text-danger py-4">${escapeHtml(data.message || 'Error loading ratings')}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error loading ratings:', error);
                    content.innerHTML = '<p class="text-center text-danger py-4">Error loading ratings. Please try again.</p>';
                });
            
            const modalElement = document.getElementById('viewRatingsModal');
            if (modalElement) {
                modalElement.removeAttribute('aria-hidden');
                modalElement.removeAttribute('inert');
            }
            modal.show();
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ==============================================
        // QUICK RATING FUNCTIONS
        // ==============================================

        function showRatingModal(employeeNumber) {
            const trigger = document.activeElement;
            if (trigger && trigger !== document.body) {
                trigger.setAttribute('data-modal-trigger', 'true');
            }
            
            document.getElementById('rateEmployeeNumber').value = employeeNumber;
            document.querySelectorAll('#ratingModal .rating-option').forEach(opt => opt.classList.remove('selected'));
            document.querySelectorAll('#ratingModal input[name="rating"]').forEach(radio => radio.checked = false);
            const categorySelect = document.querySelector('#ratingModal select[name="category"]');
            if (categorySelect) categorySelect.value = '';
            const commentsTextarea = document.querySelector('#ratingModal textarea[name="comments"]');
            if (commentsTextarea) commentsTextarea.value = '';
            
            document.querySelectorAll('#ratingModal .rating-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('#ratingModal .rating-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    const radio = this.previousElementSibling;
                    if (radio) radio.checked = true;
                });
            });
            
            const modalElement = document.getElementById('ratingModal');
            if (modalElement) {
                modalElement.removeAttribute('aria-hidden');
                modalElement.removeAttribute('inert');
            }
            const modal = new bootstrap.Modal(document.getElementById('ratingModal'));
            modal.show();
        }

        function submitQuickRating(event) {
            const form = document.getElementById('ratingForm');
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', csrfToken);
            
            const category = formData.get('category');
            if (!category) { showMessage('Please select a category', 'error'); return; }
            
            const ratingSelected = document.querySelector('input[name="rating"]:checked');
            if (!ratingSelected) { showMessage('Please select a rating', 'error'); return; }
            
            const comments = formData.get('comments');
            if (!comments || comments.trim() === '') { showMessage('Please provide comments', 'error'); return; }
            
            const submitBtn = event.target;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            submitBtn.disabled = true;
            
            fetch('{{ route("supervisor.rate-employee") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Rating submitted successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('ratingModal')).hide();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showMessage(data.message || 'Error submitting rating.', 'error');
                }
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Network error occurred. Please try again.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // ==============================================
        // RETURN MODAL FUNCTIONS
        // ==============================================

        function showReturnModal() {
            const trigger = document.activeElement;
            if (trigger && trigger !== document.body) {
                trigger.setAttribute('data-modal-trigger', 'true');
            }
            const modal = new bootstrap.Modal(document.getElementById('returnModal'));
            const modalElement = document.getElementById('returnModal');
            if (modalElement) {
                modalElement.removeAttribute('aria-hidden');
                modalElement.removeAttribute('inert');
            }
            modal.show();
        }

        // ==============================================
        // UTILITY FUNCTIONS
        // ==============================================

        function showMessage(message, type = 'info') {
            const messageContainer = document.getElementById('messageContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message message-${type}`;
            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle', warning: 'fa-exclamation-triangle' };
            messageDiv.innerHTML = `<div class="d-flex align-items-center"><i class="message-icon fas ${icons[type]} me-2"></i><div class="message-content">${escapeHtml(message)}</div></div><button class="message-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
            messageContainer.appendChild(messageDiv);
            setTimeout(() => {
                if (messageDiv.parentElement) {
                    messageDiv.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => { if (messageDiv.parentElement) messageDiv.remove(); }, 300);
                }
            }, 5000);
        }

        // ==============================================
        // INITIALIZE FOLLOW-UP EVENT LISTENERS
        // ==============================================

        // Initialize event listeners when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Set up follow-up comment button
            const submitFollowupBtn = document.getElementById('submitFollowupBtn');
            if (submitFollowupBtn) {
                // Remove any existing listeners to prevent duplicates
                const newBtn = submitFollowupBtn.cloneNode(true);
                submitFollowupBtn.parentNode.replaceChild(newBtn, submitFollowupBtn);
                newBtn.addEventListener('click', submitFollowupComment);
            }
            
            // Load follow-up history if PIP details modal exists
            if (document.getElementById('pipDetailsModal')) {
                loadFollowupHistory();
            }
            
            // Also reload history when modal is opened
            const pipDetailsModal = document.getElementById('pipDetailsModal');
            if (pipDetailsModal) {
                pipDetailsModal.addEventListener('shown.bs.modal', function() {
                    loadFollowupHistory();
                });
            }
        });

        // Document ready for other modals
        document.addEventListener('DOMContentLoaded', function() {
            commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            pipModal = new bootstrap.Modal(document.getElementById('pipModal'));
            pipDetailsModal = new bootstrap.Modal(document.getElementById('pipDetailsModal'));
            
            const modals = ['commentModal', 'kpaRatingModal', 'viewRatingsModal', 'ratingModal', 'returnModal', 'pipModal', 'pipDetailsModal'];
            modals.forEach(modalId => {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    modalElement.addEventListener('hidden.bs.modal', function() { cleanupModalFocus(modalElement); });
                }
            });
            
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => { const bsAlert = new bootstrap.Alert(alert); bsAlert.close(); });
            }, 5000);
            
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });
            
            function setTableDataLabels() {
                if (window.innerWidth <= 768) {
                    const headers = document.querySelectorAll('.table-moic thead th');
                    const rows = document.querySelectorAll('.table-moic tbody tr');
                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        cells.forEach((cell, index) => { if (headers[index]) cell.setAttribute('data-label', headers[index].textContent.trim()); });
                    });
                }
            }
            setTableDataLabels();
            window.addEventListener('resize', setTableDataLabels);
        });

     // ==============================================
// PIP SIGNATURE FUNCTIONS (for PIP section only)
// ==============================================

let pipEmployeePad = null;
let pipSupervisorPad = null;

// Initialize signature pads
function initSignaturePads() {
    console.log('Initializing signature pads...');
    
    const empCanvas = document.getElementById('pipEmployeeSignatureCanvas');
    const supCanvas = document.getElementById('pipSupervisorSignatureCanvas');
    
    if (empCanvas) {
        console.log('Employee canvas found');
        // Clear canvas
        empCanvas.width = empCanvas.clientWidth;
        empCanvas.height = 150;
        
        if (typeof SignaturePad !== 'undefined') {
            pipEmployeePad = new SignaturePad(empCanvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: '#110484',
                minWidth: 1,
                maxWidth: 2.5
            });
            console.log('Employee signature pad initialized');
        } else {
            console.error('SignaturePad library not loaded!');
        }
    } else {
        console.log('Employee canvas not found (may not be visible yet)');
    }
    
    if (supCanvas) {
        console.log('Supervisor canvas found');
        supCanvas.width = supCanvas.clientWidth;
        supCanvas.height = 150;
        
        if (typeof SignaturePad !== 'undefined') {
            pipSupervisorPad = new SignaturePad(supCanvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: '#e7581c',
                minWidth: 1,
                maxWidth: 2.5
            });
            console.log('Supervisor signature pad initialized');
        }
    } else {
        console.log('Supervisor canvas not found (may not be visible yet)');
    }
}

function clearPIPSignature(role) {
    if (role === 'employee' && pipEmployeePad) {
        pipEmployeePad.clear();
        showMessage('Signature cleared', 'info');
    } else if (role === 'supervisor' && pipSupervisorPad) {
        pipSupervisorPad.clear();
        showMessage('Signature cleared', 'info');
    } else {
        showMessage('Signature pad not ready', 'error');
    }
}

async function submitPIPSignature(role) {
    let pad = role === 'employee' ? pipEmployeePad : pipSupervisorPad;
    
    if (!pad) {
        showMessage('Signature pad not ready. Please refresh the page.', 'error');
        console.error('Signature pad not initialized for role:', role);
        return;
    }
    
    if (pad.isEmpty()) {
        showMessage('Please draw your signature in the box above before submitting', 'warning');
        return;
    }
    
    try {
        const signatureDataURL = pad.toDataURL('image/png');
        if (!signatureDataURL || signatureDataURL === 'data:,') {
            showMessage('Invalid signature data', 'error');
            return;
        }
        
        // Show loading state on button
        let targetBtn = null;
        const btns = document.querySelectorAll('.btn-submit-signature');
        btns.forEach(btn => {
            const btnText = btn.innerText || btn.textContent;
            if (role === 'employee' && btnText.includes('Sign PIP')) {
                targetBtn = btn;
            } else if (role === 'supervisor' && btnText.includes('Approve')) {
                targetBtn = btn;
            }
        });
        
        const originalText = targetBtn ? targetBtn.innerHTML : 'Submit';
        if (targetBtn) {
            targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            targetBtn.disabled = true;
        }
        
        // Create FormData
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('signature_role', role);
        formData.append('signature_data', signatureDataURL);
        formData.append('appraisal_id', '{{ $appraisal->id }}');
        
        const appraisalId = '{{ $appraisal->id }}';
        const url = `/appraisals/${appraisalId}/save-pip-signature`;
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage(`${role === 'employee' ? 'Employee' : 'Supervisor'} signature saved successfully!`, 'success');
            
            // Update the signature card to show the image
            const signatureCard = role === 'employee' 
                ? document.querySelector('.pip-signature-card:first-child')
                : document.querySelector('.pip-signature-card:last-child');
            
            if (signatureCard) {
                const signatureBody = signatureCard.querySelector('.pip-signature-body');
                if (signatureBody) {
                    signatureBody.innerHTML = `
                        <div class="text-center py-2">
                            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                            <p class="fw-bold mb-0 text-success">Electronically Signed by ${role === 'employee' ? 'Employee' : 'Supervisor'}</p>
                            <p class="small text-muted">${new Date().toLocaleString()}</p>
                            <div class="border rounded p-2 mt-2 bg-light">
                                <img src="${data.signature_url}" style="max-height:60px; max-width:100%;" alt="Signature">
                            </div>
                        </div>
                    `;
                }
                
                // Update status badge
                const statusSpan = signatureCard.querySelector('.signature-status');
                if (statusSpan) {
                    statusSpan.className = 'signature-status signature-status-signed';
                    statusSpan.innerHTML = '<i class="fas fa-check-circle me-1"></i>Signed';
                }
            }
            
            // Reload after 2 seconds to show persisted data
            setTimeout(() => window.location.reload(), 2000);
        } else {
            showMessage(data.message || 'Failed to save signature', 'error');
            if (targetBtn) {
                targetBtn.innerHTML = originalText;
                targetBtn.disabled = false;
            }
        }
    } catch (error) {
        console.error('Signature error:', error);
        showMessage('Error: ' + error.message, 'error');
        if (targetBtn) {
            targetBtn.innerHTML = originalText;
            targetBtn.disabled = false;
        }
    }
}

// Initialize when modal is opened (since canvases might not exist on page load)
document.addEventListener('DOMContentLoaded', function() {
    // Try to initialize immediately if canvases exist
    if (document.getElementById('pipEmployeeSignatureCanvas') || document.getElementById('pipSupervisorSignatureCanvas')) {
        initSignaturePads();
    }
    
    // Also initialize when PIP details modal is opened
    const pipDetailsModal = document.getElementById('pipDetailsModal');
    if (pipDetailsModal) {
        pipDetailsModal.addEventListener('shown.bs.modal', function() {
            console.log('PIP Modal opened, initializing signature pads...');
            setTimeout(initSignaturePads, 100); // Small delay to ensure canvas is rendered
        });
    }
});

// Show existing signature previews
@if($pipEmployeeSigned && $appraisal->pip_employee_signature_path)
    document.addEventListener('DOMContentLoaded', function() {
        const empPreview = document.getElementById('pipEmployeeSignaturePreview');
        if (empPreview) {
            empPreview.innerHTML = '<img src="{{ asset($appraisal->pip_employee_signature_path) }}" style="max-height:60px;">';
        }
    });
@endif
@if($pipSupervisorSigned && $appraisal->pip_supervisor_signature_path)
    document.addEventListener('DOMContentLoaded', function() {
        const supPreview = document.getElementById('pipSupervisorSignaturePreview');
        if (supPreview) {
            supPreview.innerHTML = '<img src="{{ asset($appraisal->pip_supervisor_signature_path) }}" style="max-height:60px;">';
        }
    });
@endif
    </script>
</body>
</html>