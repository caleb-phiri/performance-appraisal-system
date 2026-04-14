<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AppraisalController;
use App\Http\Controllers\SupervisorDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminAppraisalController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\AppraisalQuarterlySummaryController;
// Welcome page
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [EmployeeLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [EmployeeLoginController::class, 'login'])->name('employee.login');
    
    // Onboarding routes (public)
    Route::get('/onboarding/survey', [OnboardingController::class, 'showSurvey'])->name('onboarding.survey');
    Route::post('/onboarding/survey', [OnboardingController::class, 'submitSurvey'])->name('onboarding.submit');
    
    // AJAX routes for onboarding
    Route::post('/onboarding/check-employee', [OnboardingController::class, 'checkEmployeeNumber'])->name('onboarding.check');
    Route::get('/onboarding/supervisors', [OnboardingController::class, 'getSupervisors'])->name('onboarding.supervisors');
    
    // Password status check route
    Route::post('/check-password-status', function (Request $request) {
        $request->validate([
            'employee_number' => 'required|string',
        ]);
        
        $user = \App\Models\User::where('employee_number', $request->employee_number)->first();
        
        if (!$user) {
            return response()->json([
                'exists' => false,
                'has_password' => false,
                'message' => 'Employee not found'
            ]);
        }
        
        // Check if user is onboarded
        $isOnboarded = true;
        if (isset($user->onboarded)) {
            $isOnboarded = $user->onboarded;
        } elseif (isset($user->is_onboarded)) {
            $isOnboarded = $user->is_onboarded;
        } elseif (isset($user->onboarded_at)) {
            $isOnboarded = !empty($user->onboarded_at);
        }
        
        if (!$isOnboarded) {
            return response()->json([
                'exists' => true,
                'onboarded' => false,
                'has_password' => false,
                'message' => 'Please complete profile setup first'
            ]);
        }
        
        return response()->json([
            'exists' => true,
            'onboarded' => true,
            'has_password' => !empty($user->password),
            'requires_password_setup' => empty($user->password) && 
                                       (isset($user->password_setup_skipped) ? !$user->password_setup_skipped : true),
            'message' => !empty($user->password) ? 'Password required' : 'No password set'
        ]);
    })->name('check.password.status');
});

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');
    
    // Dashboard for normal users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Password management routes
    Route::get('/profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password');
    Route::post('/profile/password/setup', [ProfileController::class, 'setupPassword'])->name('profile.password.setup');
    Route::post('/profile/password/skip', [ProfileController::class, 'skipPasswordSetup'])->name('profile.password.skip');
    Route::put('/profile/password/update', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Fetch supervisors and toll plazas
    Route::get('/profile/supervisors', [ProfileController::class, 'getSupervisors'])->name('profile.supervisors');
    Route::get('/profile/toll-plazas', [ProfileController::class, 'getTollPlazas'])->name('profile.toll-plazas');
    
// Appraisal routes
Route::prefix('appraisals')->name('appraisals.')->group(function () {
    // QUARTERLY SUMMARY ROUTES - MUST COME FIRST
    Route::get('/quarterly-summary', [AppraisalQuarterlySummaryController::class, 'index'])
        ->name('quarterly-summary.index');
    
    Route::get('/quarterly-summary/download', [AppraisalQuarterlySummaryController::class, 'download'])
        ->name('quarterly-summary.download');
    
    // Main CRUD routes - The {appraisal} parameter route must come AFTER specific routes
    Route::get('/', [AppraisalController::class, 'index'])->name('index');
    Route::get('/create', [AppraisalController::class, 'create'])->name('create');
    Route::post('/', [AppraisalController::class, 'store'])->name('store');
    
    // Form-specific routes - ALL forms go here with consistent pattern
    Route::get('/form/plaza-manager', [AppraisalController::class, 'plazaManager'])->name('plaza-manager');
    Route::get('/form/em-technician', [AppraisalController::class, 'emTechnician'])->name('em-technician');
    Route::get('/form/admin-clerk', [AppraisalController::class, 'adminClerk'])->name('admin-clerk');
    Route::get('/form/shift-manager', [AppraisalController::class, 'shiftManager'])->name('shift-manager');
    Route::get('/form/senior-toll-collector', [AppraisalController::class, 'seniorTollCollector'])->name('senior-toll-collector');
    Route::get('/form/toll-collector', [AppraisalController::class, 'tollCollector'])->name('toll-collector');
    Route::get('/form/tce', [AppraisalController::class, 'tce'])->name('tce');
    Route::get('/form/route-patrol-driver', [AppraisalController::class, 'routePatrolDriver'])->name('route-patrol-driver');
    Route::get('/form/plaza-attendant', [AppraisalController::class, 'plazaAttendant'])->name('plaza-attendant');
    Route::get('/form/lane-attendant', [AppraisalController::class, 'laneAttendant'])->name('lane-attendant');
    Route::get('/form/hr-assistant', [AppraisalController::class, 'hrAssistant'])->name('hr-assistant');
    Route::get('/form/hr-advisor', [AppraisalController::class, 'hrAdvisor'])->name('hr-advisor');
    Route::get('/form/verification-clerk', [AppraisalController::class, 'verificationclerk'])->name('verification-clerk');
    
    // NEW MISSING FORMS - Follow the same pattern
    Route::get('/form/admin-manager', [AppraisalController::class, 'adminManager'])->name('admin-manager');
    Route::get('/form/trainer', [AppraisalController::class, 'trainer'])->name('trainer');
    Route::get('/form/senior-trainer', [AppraisalController::class, 'seniorTrainer'])->name('senior-trainer');
    Route::get('/form/senior-tce', [AppraisalController::class, 'seniorTce'])->name('senior-tce');
    Route::get('/form/media-coordinator', [AppraisalController::class, 'mediaCoordinator'])->name('media-coordinator');
    
    // Parameter routes - These must come LAST
    Route::get('/{appraisal}', [AppraisalController::class, 'show'])->name('show');
    Route::get('/{appraisal}/edit', [AppraisalController::class, 'edit'])->name('edit');
    Route::put('/{appraisal}', [AppraisalController::class, 'update'])->name('update');
    Route::delete('/{appraisal}', [AppraisalController::class, 'destroy'])->name('destroy');
    Route::post('/{appraisal}/submit', [AppraisalController::class, 'submit'])->name('submit');
    Route::post('/{appraisal}/add-comment', [AppraisalController::class, 'addComment'])->name('add-comment');
    Route::post('/{appraisal}/approve', [AppraisalController::class, 'approve'])->name('approve');
    Route::post('/{appraisal}/return', [AppraisalController::class, 'returnForRevision'])->name('return');
   // ⭐⭐⭐ FIXED PIP ROUTES - INSIDE THE GROUP (no duplicate prefix) ⭐⭐⭐
    Route::post('/{appraisal}/initiate-pip', [AppraisalController::class, 'initiatePIP'])
        ->name('initiate-pip');
    
    Route::get('/{appraisal}/pip-details', [AppraisalController::class, 'getPIPDetails'])
        ->name('pip-details');

       // PIP Management Routes
Route::middleware(['auth'])->group(function () {
    // View all PIPs (Supervisors see their team, Admins see all)
    Route::get('/pip-management', [AppraisalController::class, 'pipManagement'])
        ->name('pip.management');
    
    // View single PIP details
    Route::get('/pip/{appraisal}', [AppraisalController::class, 'showPipDetails'])
        ->name('pip.show');
    
    // Update PIP status
    Route::put('/pip/{appraisal}/update-status', [AppraisalController::class, 'updatePipStatus'])
        ->name('pip.update-status');
    
    // Export PIPs to Excel/CSV
    Route::get('/pip/export', [AppraisalController::class, 'exportPips'])
        ->name('pip.export');
}); 
});
    
    // KPA rating route
    Route::post('/kpa/rate', [AppraisalController::class, 'rateKpa'])->name('kpa.rate');
    
    // Supervisor Routes
    Route::prefix('supervisor')->name('supervisor.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');
        
        // Team Management
        Route::get('/team', [SupervisorDashboardController::class, 'team'])->name('team');
        
        // Reports
        Route::get('/reports', [SupervisorDashboardController::class, 'reports'])->name('reports');
        
        // Appeals
        Route::get('/appeals/{id}', [SupervisorDashboardController::class, 'getAppeal'])->name('appeals.show');
        Route::post('/appeals/{id}/start-review', [SupervisorDashboardController::class, 'startAppealReview'])->name('appeals.start-review');
        Route::post('/appeals/{id}/comment', [SupervisorDashboardController::class, 'addAppealComment'])->name('appeals.comment');
        Route::post('/appeals/{id}/resolve', [SupervisorDashboardController::class, 'resolveAppeal'])->name('appeals.resolve');
        
        // Ratings comments
        Route::get('/ratings/comments', [SupervisorDashboardController::class, 'getRatingComments'])->name('ratings.comments');
        Route::get('/rating-history', [SupervisorDashboardController::class, 'getRatingComments'])->name('rating-history');
    });
    
    // Supervisor rating route
    Route::post('/supervisor/rate-employee', [SupervisorDashboardController::class, 'rateEmployee'])
        ->name('supervisor.rate-employee');
    
    // Supervisor review route
    Route::get('/supervisor/review/{appraisal}', [AppraisalController::class, 'review'])
        ->name('supervisor.review');
    
    // Additional appraisal routes
    Route::post('/appraisals/{id}/reject', [AppraisalController::class, 'reject'])
        ->name('appraisals.reject');
});

// ADMIN ROUTES - SINGLE CLEAN GROUP
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        // Main routes
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/inactive', [AdminController::class, 'inactiveUsers'])->name('inactive');
        Route::get('/search', [AdminController::class, 'search'])->name('search');
        Route::get('/export', [AdminController::class, 'exportUsers'])->name('export');
        
        // Individual user routes
        Route::get('/{employeeNumber}', [AdminController::class, 'showUser'])->name('show');
        Route::get('/{employeeNumber}/reset-password', [AdminController::class, 'resetPasswordForm'])->name('reset-password');
        Route::post('/{employeeNumber}/reset-password', [AdminController::class, 'resetPassword']);
        Route::post('/{employeeNumber}/remove-password', [AdminController::class, 'removePassword'])->name('remove-password');
    });
    
    // Appraisal Monitoring Routes
    Route::prefix('appraisals')->name('appraisals.')->group(function () {
        Route::get('/', [AdminController::class, 'allAppraisals'])->name('index');
        Route::get('/search', [AdminController::class, 'searchAppraisals'])->name('search');
        Route::get('/supervisor/{supervisor}', [AdminController::class, 'supervisorAppraisals'])->name('supervisor');
        Route::get('/report', [AdminController::class, 'appraisalReport'])->name('report');
        Route::get('/export', [AdminController::class, 'exportAppraisals'])->name('export');
    });
    
    // Team Members (for supervisors)
    Route::get('/team-members', [AdminController::class, 'teamMembers'])->name('team-members');
    
    // Test route
    Route::get('/test', function() {
        return 'Admin test route works!';
    })->name('test');
});

// Default redirect for logged-in users
Route::get('/home', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        // Check if user needs to complete onboarding
        $isOnboarded = true;
        if (isset($user->onboarded)) {
            $isOnboarded = $user->onboarded;
        } elseif (isset($user->is_onboarded)) {
            $isOnboarded = $user->is_onboarded;
        }
        
        if (!$isOnboarded) {
            return redirect()->route('profile.show')
                ->with('warning', 'Please complete your profile setup first.');
        }
        
        // Check if user needs to set up password
        if (empty($user->password) && 
            (!isset($user->password_setup_skipped) || !$user->password_setup_skipped)) {
            return redirect()->route('profile.password')
                ->with('info', 'Please set up a password for your account.');
        }
        
        // Redirect based on user type
        if (isset($user->user_type)) {
            if ($user->user_type === 'supervisor' || $user->user_type === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        
        return redirect()->route('dashboard');
    }
    
    return redirect()->route('login');
})->name('home');

// In your routes/web.php, make sure the route is defined like this:
Route::get('/appraisals/supervisor/{employeeNumber}', [AdminController::class, 'supervisorAppraisals'])->name('appraisals.supervisor');

// Reactivate user
Route::post('/admin/users/{employeeNumber}/reactivate', [AdminController::class, 'reactivate'])
    ->name('admin.users.reactivate');

    // Toggle user active status
Route::post('/admin/users/{employeeNumber}/toggle-status', [AdminController::class, 'toggleStatus'])
    ->name('admin.users.toggle-status');

    // routes/web.php

// Admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/inactive', [AdminController::class, 'inactiveUsers'])->name('admin.users.inactive');
    Route::get('/users/{employeeNumber}', [AdminController::class, 'showUser'])->name('admin.users.show');
    
    // Password management
    Route::get('/users/{employeeNumber}/reset-password', [AdminController::class, 'resetPasswordForm'])->name('admin.users.reset-password');
    Route::post('/users/{employeeNumber}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.reset-password.post');
    Route::post('/users/{employeeNumber}/remove-password', [AdminController::class, 'removePassword'])->name('admin.users.remove-password');
    
    // User status management (ALL AS POST REQUESTS)
    Route::post('/users/{employeeNumber}/reactivate', [AdminController::class, 'reactivate'])->name('admin.users.reactivate');
    Route::post('/users/{employeeNumber}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    
    // Mark as left (if you have this functionality)
    Route::post('/users/{employeeNumber}/mark-as-left', [AdminController::class, 'markAsLeft'])->name('admin.users.mark-as-left');
    
    // Search
    Route::get('/users/search', [AdminController::class, 'search'])->name('admin.users.search');
    
    // Team members (for supervisors)
    Route::get('/team-members', [AdminController::class, 'teamMembers'])->name('admin.team-members');
    
    // Appraisals
    Route::get('/appraisals', [AdminController::class, 'allAppraisals'])->name('admin.appraisals.index');
    Route::get('/appraisals/search', [AdminController::class, 'searchAppraisals'])->name('admin.appraisals.search');
    Route::get('/appraisals/supervisor/{supervisorId}', [AdminController::class, 'supervisorAppraisals'])->name('admin.appraisals.supervisor');
    Route::get('/appraisals/report', [AdminController::class, 'appraisalReport'])->name('admin.appraisals.report');
    Route::get('/appraisals/export', [AdminController::class, 'exportAppraisals'])->name('admin.appraisals.export');
    
    // Export users
    Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');


});
// In routes/web.php

// KPA Rating Routes
Route::post('/kpa/rate', [AppraisalController::class, 'rateKpa'])->name('kpa.rate');
Route::post('/kpa/rate-multiple', [AppraisalController::class, 'rateKpaMultiple'])->name('kpa.rate-multiple');

// If you have a separate controller for KPA ratings
Route::post('/kpa/{kpa}/rate', [KPAController::class, 'rate'])->name('kpa.rate');
Route::post('/kpa/{kpa}/rate-multiple', [KPAController::class, 'rateMultiple'])->name('kpa.rate-multiple');

// Supervisor assignment routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Supervisor assignments page
    Route::get('/supervisor-assignments', [AdminController::class, 'supervisorAssignments'])
        ->name('admin.supervisor-assignments');
    
    // Get employee's supervisors
    Route::get('/employee/{employeeNumber}/supervisors', [AdminController::class, 'getEmployeeSupervisors'])
        ->name('admin.employee.supervisors');
    
    // Update employee's supervisors
    Route::post('/employee/{employeeNumber}/supervisors', [AdminController::class, 'updateEmployeeSupervisors'])
        ->name('admin.employee.update-supervisors');
    
    // Search users for assignment
    Route::get('/users/search', [AdminController::class, 'search'])
        ->name('admin.users.search');
});
// Change FROM:
Route::get('/admin/users/search', [AdminController::class, 'search'])
    ->name('admin.users.search');

// Change TO:
Route::get('/admin/users/search', [AdminController::class, 'searchUsers'])
    ->name('admin.users.search');

   // routes/web.php - Add these routes

// KPA Rating Routes
Route::post('/kpa/rate', [KPAController::class, 'rate'])->name('kpa.rate');
Route::post('/kpa/rate-multiple', [KPAController::class, 'rateMultiple'])->name('kpa.rate-multiple');
Route::get('/kpa/{kpaId}/ratings', [KPAController::class, 'getKpaRatings'])->name('kpa.ratings');
Route::get('/appraisal/{appraisalId}/rating-stats', [KPAController::class, 'getRatingStats'])->name('appraisal.rating-stats');

// Supervisor Assignment Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/supervisor-assignments', [AdminController::class, 'supervisorAssignments'])
        ->name('admin.supervisor-assignments');
    Route::get('/employee/{employeeNumber}/supervisors', [AdminController::class, 'getEmployeeSupervisors'])
        ->name('admin.employee.supervisors');
    Route::post('/employee/{employeeNumber}/supervisors', [AdminController::class, 'updateEmployeeSupervisors'])
        ->name('admin.employee.update-supervisors');
});

// routes/web.php - Make sure you have these routes

use App\Http\Controllers\KPAController;

// KPA Rating Routes
Route::post('/kpa/rate', [KPAController::class, 'rate'])->name('kpa.rate');
Route::post('/kpa/rate-multiple', [KPAController::class, 'rateMultiple'])->name('kpa.rate-multiple');
Route::get('/kpa/{kpaId}/ratings', [KPAController::class, 'getKpaRatings'])->name('kpa.ratings');
Route::get('/appraisal/{appraisalId}/rating-stats', [KPAController::class, 'getRatingStats'])->name('appraisal.rating-stats');

// routes/web.php - Add this route

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // ... existing routes ...
    
    // Supervisor assignment management
    Route::get('/supervisor-assignments', [AdminController::class, 'supervisorAssignments'])
        ->name('admin.supervisor-assignments');
    
    Route::get('/employee/{employeeNumber}/supervisors', [AdminController::class, 'getEmployeeSupervisors'])
        ->name('admin.employee.supervisors');
    
    Route::post('/employee/{employeeNumber}/supervisors', [AdminController::class, 'updateEmployeeSupervisors'])
        ->name('admin.employee.update-supervisors');
    
    Route::get('/users/search', [AdminController::class, 'searchUsers'])
        ->name('admin.users.search');
});

// User management routes
Route::prefix('admin/users')->name('admin.users.')->group(function () {
    // Reactivate route with PUT method
    Route::put('/{user}/reactivate', [AdminController::class, 'reactivate'])
        ->name('reactivate');
    
    // Mark as left route
    Route::put('/{user}/mark-as-left', [AdminController::class, 'markAsLeft'])
        ->name('mark-as-left');
    
    // Resource routes for other operations
    Route::get('/', [AdminController::class, 'users'])->name('index');
    Route::get('/inactive', [AdminController::class, 'inactiveUsers'])->name('inactive');
    Route::get('/{user}', [AdminController::class, 'showUser'])->name('show');
    Route::get('/{user}/reset-password', [AdminController::class, 'resetPasswordForm'])->name('reset-password');
    Route::post('/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('reset-password.post');
    Route::post('/{user}/remove-password', [AdminController::class, 'removePassword'])->name('remove-password');
    Route::put('/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle-status');
});

// Leave Application Routes
Route::middleware(['auth'])->prefix('leave')->name('leave.')->group(function () {
    Route::get('/', [App\Http\Controllers\LeaveController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\LeaveController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\LeaveController::class, 'store'])->name('store');
    Route::get('/{leave}', [App\Http\Controllers\LeaveController::class, 'show'])->name('show');
    Route::get('/{leave}/edit', [App\Http\Controllers\LeaveController::class, 'edit'])->name('edit');
    Route::put('/{leave}', [App\Http\Controllers\LeaveController::class, 'update'])->name('update');
    Route::delete('/{leave}', [App\Http\Controllers\LeaveController::class, 'destroy'])->name('destroy');
});

// Supervisor Leave Management Routes

    // ... existing supervisor routes ...
    
    Route::get('/leaves', [SupervisorController::class, 'leaves'])->name('leaves');
    Route::post('/leaves/{id}/approve', [SupervisorController::class, 'approveLeave'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [SupervisorController::class, 'rejectLeave'])->name('leaves.reject');
    Route::post('/leaves/{id}/cancel', [SupervisorController::class, 'cancelLeave'])->name('leaves.cancel');

Route::prefix('supervisor')->group(function () {
        // Supervisor Dashboard
        Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])
            ->name('supervisor.dashboard');
        
        // Leave Management Routes
        Route::prefix('leaves')->group(function () {
            Route::get('/', [SupervisorDashboardController::class, 'leaves'])
                ->name('supervisor.leaves');
            Route::post('/{leave}/approve', [SupervisorDashboardController::class, 'approveLeave'])
                ->name('supervisor.leaves.approve');
            Route::post('/{leave}/reject', [SupervisorDashboardController::class, 'rejectLeave'])
                ->name('supervisor.leaves.reject');
            Route::post('/{leave}/cancel', [SupervisorDashboardController::class, 'cancelLeave'])
                ->name('supervisor.leaves.cancel');
            Route::get('/export', [SupervisorDashboardController::class, 'exportLeaves'])
                ->name('supervisor.leaves.export');
        });
        });

        // Individual leave view
Route::get('/leaves/{leave}', [App\Http\Controllers\LeaveController::class, 'show'])
    ->name('leaves.show')
    ->middleware('auth');

// Or if you want it in SupervisorDashboardController:
Route::get('/supervisor/leaves/{leave}', [App\Http\Controllers\SupervisorDashboardController::class, 'showLeave'])
    ->name('supervisor.leaves.show')
    ->middleware('auth');
    

    // Supervisor Dashboard Routes
Route::prefix('supervisor')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\SupervisorDashboardController::class, 'index'])
        ->name('supervisor.dashboard');
    
    // Leaves Management
    Route::get('/leaves', [App\Http\Controllers\SupervisorDashboardController::class, 'leaves'])
        ->name('supervisor.leaves');
    
    Route::post('/leaves/{id}/approve', [App\Http\Controllers\SupervisorDashboardController::class, 'approveLeave'])
        ->name('supervisor.leaves.approve');
    
    Route::post('/leaves/{id}/reject', [App\Http\Controllers\SupervisorDashboardController::class, 'rejectLeave'])
        ->name('supervisor.leaves.reject');
    
    Route::post('/leaves/{id}/cancel', [App\Http\Controllers\SupervisorDashboardController::class, 'cancelLeave'])
        ->name('supervisor.leaves.cancel');
    
    Route::get('/leaves/{id}/details', [App\Http\Controllers\SupervisorDashboardController::class, 'getLeaveDetails'])
        ->name('supervisor.leaves.details');
    
    // Team
    Route::get('/team', [App\Http\Controllers\SupervisorDashboardController::class, 'team'])
        ->name('supervisor.team');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\SupervisorDashboardController::class, 'reports'])
        ->name('supervisor.reports');
    
    // Appeals
    Route::get('/appeals/{id}', [App\Http\Controllers\SupervisorDashboardController::class, 'getAppeal'])
        ->name('supervisor.appeals.show');
    
    Route::post('/appeals/{id}/start-review', [App\Http\Controllers\SupervisorDashboardController::class, 'startAppealReview'])
        ->name('supervisor.appeals.start-review');
    
    Route::post('/appeals/{id}/comment', [App\Http\Controllers\SupervisorDashboardController::class, 'addAppealComment'])
        ->name('supervisor.appeals.comment');
    
    Route::post('/appeals/{id}/resolve', [App\Http\Controllers\SupervisorDashboardController::class, 'resolveAppeal'])
        ->name('supervisor.appeals.resolve');
    
    // Ratings
    Route::get('/ratings/comments', [App\Http\Controllers\SupervisorDashboardController::class, 'getRatingComments'])
        ->name('supervisor.ratings.comments');
    
    Route::post('/ratings/quick', [App\Http\Controllers\SupervisorDashboardController::class, 'quickRate'])
        ->name('supervisor.ratings.quick');
    
    Route::post('/ratings', [App\Http\Controllers\SupervisorDashboardController::class, 'rateEmployee'])
        ->name('supervisor.ratings.create');
});


Route::get('/leaves/{id}/next-approvers', [LeaveController::class, 'getNextApprovers'])->name('leaves.next-approvers');

// Leave routes
Route::resource('leaves', LeaveController::class);
Route::get('/leaves/{id}/next-approvers', [LeaveController::class, 'getNextApprovers'])->name('leaves.next-approvers');
Route::post('/supervisor/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('supervisor.leaves.approve');
Route::post('/supervisor/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('supervisor.leaves.reject');
Route::get('/pending-approvals', [LeaveController::class, 'pendingApprovals'])->name('leave.pending-approvals');

// Leave routes
Route::resource('leaves', LeaveController::class);
Route::get('/leaves/{id}/next-approvers', [LeaveController::class, 'getNextApprovers'])->name('leaves.next-approvers');

// Supervisor leave approval routes
Route::prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/leaves', [SupervisorDashboardController::class, 'leaves'])->name('leaves');
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::post('/leaves/{id}/cancel', [SupervisorDashboardController::class, 'cancelLeave'])->name('leaves.cancel');
});

// Add this with your other profile routes
Route::put('/profile/update-supervisor', [App\Http\Controllers\ProfileController::class, 'updateSupervisor'])
    ->name('profile.update-supervisor');

    Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::get('/password', [ProfileController::class, 'password'])->name('password');
    Route::post('/password/setup', [ProfileController::class, 'setupPassword'])->name('password.setup');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/password/skip', [ProfileController::class, 'skipPassword'])->name('password.skip');
    
    // IMPORTANT: Add these two routes for supervisor functionality
    Route::put('/update-supervisor', [ProfileController::class, 'updateSupervisor'])->name('update-supervisor');
    Route::get('/supervisors', [ProfileController::class, 'getSupervisors'])->name('supervisors');
    
    // Other routes...
});

Route::middleware(['auth'])->group(function () {
    Route::get('/supervisor/export-subordinate-appraisals', [App\Http\Controllers\SupervisorController::class, 'exportSubordinateAppraisals'])
        ->name('supervisor.export-subordinate-appraisals');
});
// Supervisor routes
Route::middleware(['auth'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SupervisorController::class, 'dashboard'])->name('dashboard');
    Route::get('/team', [App\Http\Controllers\SupervisorController::class, 'team'])->name('team');
    Route::get('/reports', [App\Http\Controllers\SupervisorController::class, 'reports'])->name('reports');
    Route::get('/leaves', [App\Http\Controllers\SupervisorController::class, 'leaves'])->name('leaves');
    Route::get('/export-subordinate-appraisals', [App\Http\Controllers\SupervisorController::class, 'exportSubordinateAppraisals'])->name('export-subordinate-appraisals');
    
    // API routes for AJAX
    Route::get('/member/{employeeNumber}/appraisals', [App\Http\Controllers\SupervisorController::class, 'getMemberAppraisals'])->name('member.appraisals');
    Route::get('/team-hierarchy', [App\Http\Controllers\SupervisorController::class, 'getTeamHierarchy'])->name('team.hierarchy');
    
    // Leave management actions
    Route::post('/leaves/{id}/approve', [App\Http\Controllers\SupervisorController::class, 'approveLeave'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [App\Http\Controllers\SupervisorController::class, 'rejectLeave'])->name('leaves.reject');
    Route::post('/leaves/{id}/cancel', [App\Http\Controllers\SupervisorController::class, 'cancelLeave'])->name('leaves.cancel');
    
    // Quick rating
    Route::post('/rate-employee', [App\Http\Controllers\SupervisorController::class, 'rateEmployee'])->name('rate.employee');
});
// Appraisal routes
Route::middleware(['auth'])->group(function () {
    // ... your existing routes ...
    
    // Add this route for reviewing appraisals
    Route::get('/appraisals/{id}/review', [App\Http\Controllers\AppraisalController::class, 'review'])->name('appraisals.review');
});






// Notification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/clear-all', [App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    
    // Optional: Get unread count
    Route::get('/notifications/unread-count', function() {
        return response()->json([
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    })->name('notifications.unread-count');
});
// Notification routes
Route::get('/notifications/latest', [LeaveController::class, 'getNotifications'])->name('notifications.latest');
Route::post('/notifications/{id}/mark-as-read', [LeaveController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
Route::post('/notifications/mark-all-as-read', [LeaveController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');

Route::put('/leave/{leave}', [LeaveController::class, 'update'])->name('leave.update');

// Leave routes
Route::resource('leave', LeaveController::class);
Route::post('/leave/{leave}/cancel', [LeaveController::class, 'cancel'])->name('leave.cancel');

// Supervisor leave routes - FIXED: Make sure these are correctly defined
Route::prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/leaves', [LeaveController::class, 'supervisorIndex'])->name('leaves');
    Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
});

// Next approvers route
Route::get('/leaves/{leave}/next-approvers', [LeaveController::class, 'getNextApprovers'])->name('leaves.next-approvers');

// Notification routes
Route::get('/notifications/latest', [LeaveController::class, 'getNotifications'])->name('notifications.latest');
Route::post('/notifications/{id}/mark-as-read', [LeaveController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
Route::post('/notifications/mark-all-as-read', [LeaveController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');

// Leave availability check
Route::post('/leave/check-availability', [LeaveController::class, 'checkAvailability'])->name('leave.check-availability');


Route::middleware(['auth'])->group(function () {
    // Calendar routes
    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [App\Http\Controllers\CalendarController::class, 'getEvents'])->name('calendar.events');
    Route::get('/calendar/export', [App\Http\Controllers\CalendarController::class, 'export'])->name('calendar.export');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [App\Http\Controllers\CalendarController::class, 'getEvents'])->name('calendar.events');
    Route::get('/calendar/export', [App\Http\Controllers\CalendarController::class, 'export'])->name('calendar.export');
});
Route::get('/leave-balance', [App\Http\Controllers\LeaveBalanceController::class, 'index'])->name('leave.balance');


// ... your existing routes ...

// Supervisor Routes - Group them properly
Route::middleware(['auth'])->prefix('supervisor')->name('supervisor.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('dashboard');
    
    // Alternative if using SupervisorDashboardController
    // Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');
    
    // Team Management
    Route::get('/team', [SupervisorController::class, 'team'])->name('team');
    
    // Leave Management
    Route::get('/leaves', [SupervisorController::class, 'leaves'])->name('leaves');
    Route::post('/leaves/{id}/approve', [SupervisorController::class, 'approveLeave'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [SupervisorController::class, 'rejectLeave'])->name('leaves.reject');
    Route::post('/leaves/{id}/cancel', [SupervisorController::class, 'cancelLeave'])->name('leaves.cancel');
    
    // Reports
    Route::get('/reports', [SupervisorController::class, 'reports'])->name('reports');
    
    // Employee Rating - THIS IS THE MISSING ROUTE
    Route::post('/rate-employee', [SupervisorController::class, 'rateEmployee'])->name('rate-employee');
    
    // AJAX Routes
    Route::get('/team-hierarchy', [SupervisorController::class, 'getTeamHierarchy'])->name('team-hierarchy');
    Route::get('/member-appraisals/{employeeNumber}', [SupervisorController::class, 'getMemberAppraisals'])->name('member-appraisals');
    
    // Export Routes
    Route::get('/export-subordinate-appraisals', [SupervisorController::class, 'exportSubordinateAppraisals'])->name('export-subordinate-appraisals');
});



Route::prefix('supervisor')->middleware('auth')->name('supervisor.')->group(function () {
    Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('dashboard');
    Route::get('/team', [SupervisorController::class, 'team'])->name('team');
    Route::get('/reports', [SupervisorController::class, 'reports'])->name('reports');
    Route::get('/leaves', [SupervisorController::class, 'leaves'])->name('leaves');
    Route::get('/export-subordinate-appraisals', [SupervisorController::class, 'exportSubordinateAppraisals'])->name('export-subordinate-appraisals');

    // Leave management
    Route::post('/leaves/{id}/approve', [SupervisorController::class, 'approveLeave'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [SupervisorController::class, 'rejectLeave'])->name('leaves.reject');
    Route::post('/leaves/{id}/cancel', [SupervisorController::class, 'cancelLeave'])->name('leaves.cancel');

    // AJAX / API
    Route::get('/member/{employeeNumber}/appraisals', [SupervisorController::class, 'getMemberAppraisals'])->name('member.appraisals');
    Route::get('/team-hierarchy', [SupervisorController::class, 'getTeamHierarchy'])->name('team.hierarchy');

    // Ratings
    Route::post('/rate-employee', [SupervisorController::class, 'rateEmployee'])->name('rate-employee');
    Route::post('/ratings/quick', [SupervisorController::class, 'quickRate'])->name('ratings.quick');
});

Route::get('/supervisor/export-subordinate-appraisals', [SupervisorController::class, 'exportSubordinateAppraisals'])
    ->name('supervisor.export-subordinate-appraisals');
    // Supervisor rating routes
Route::post('/supervisor/rate-employee', [App\Http\Controllers\SupervisorRatingController::class, 'store'])
    ->name('supervisor.rate-employee')
    ->middleware(['auth']);








// For downloading all appraisals (admin only)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/appraisals/download-all', [AppraisalQuarterlySummaryController::class, 'downloadAllAppraisals'])
        ->name('admin.appraisals.download-all');
    
    Route::get('/admin/appraisals/download-raw', [AppraisalQuarterlySummaryController::class, 'downloadAllAppraisalsRaw'])
        ->name('admin.appraisals.download-raw');
});

// Add this to your routes/web.php if it doesn't exist
Route::get('/appraisals/quarterly-summary', [App\Http\Controllers\AppraisalQuarterlySummaryController::class, 'index'])
    ->name('appraisals.quarterly-summary.index');
    // For displaying the summary page
Route::get('/appraisals/quarterly-summary', [App\Http\Controllers\AppraisalQuarterlySummaryController::class, 'index'])
    ->name('appraisals.quarterly-summary.index');

// For downloading the report
Route::get('/appraisals/quarterly-summary/download', [App\Http\Controllers\AppraisalQuarterlySummaryController::class, 'download'])
    ->name('appraisals.quarterly-summary.download');

 
Route::get('/appraisals/quarterly-summary', [AppraisalQuarterlySummaryController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/appraisals/quarterly-summary', [AppraisalQuarterlySummaryController::class, 'index'])
        ->name('appraisals.quarterly-summary');

    Route::get('/appraisals/quarterly-summary/download', [AppraisalQuarterlySummaryController::class, 'download'])
        ->name('appraisals.quarterly-summary.download');
});


Route::get('/quarterly-summary', [App\Http\Controllers\AppraisalController::class, 'index'])->name('appraisals.quarterly-summary.index');
Route::get('/quarterly-summary', [App\Http\Controllers\AppraisalController::class, 'index'])
     ->name('appraisals.quarterly-summary.index');
     
     Route::get('/quarterly-summary', [App\Http\Controllers\AppraisalController::class, 'index'])
     ->name('appraisals.quarterly-summary.index');
     Route::get('/appraisals/quarterly-summary', [App\Http\Controllers\AppraisalController::class, 'index'])
     ->name('appraisals.quarterly-summary.index');
     

Route::get('/appraisals/quarterly-summary', [AppraisalController::class, 'index'])
     ->name('appraisals.quarterly-summary.index');

    // ==============================================
// APPRAISAL QUARTERLY SUMMARY ROUTES - SINGLE CLEAN DEFINITION
// ==============================================
Route::middleware(['auth'])->group(function () {
    // Quarterly Summary Routes - Define these BEFORE other appraisal routes in the group below
    Route::get('/appraisals/quarterly-summary', [AppraisalQuarterlySummaryController::class, 'index'])
        ->name('appraisals.quarterly-summary.index');
    
    Route::get('/appraisals/quarterly-summary/download', [AppraisalQuarterlySummaryController::class, 'download'])
        ->name('appraisals.quarterly-summary.download');
    
    // Admin-only download routes
    Route::middleware(['can:admin'])->group(function () {
        Route::get('/admin/appraisals/download-all', [AppraisalQuarterlySummaryController::class, 'downloadAllAppraisals'])
            ->name('admin.appraisals.download-all');
        
        Route::get('/admin/appraisals/download-raw', [AppraisalQuarterlySummaryController::class, 'downloadAllAppraisalsRaw'])
            ->name('admin.appraisals.download-raw');
        
        Route::get('/admin/appraisals/user-list', [AppraisalQuarterlySummaryController::class, 'downloadUserListWithStatus'])
            ->name('admin.appraisals.user-list');
    });
});
// Make sure these routes exist in your web.php
Route::get('/appraisals/{appraisal}/edit', [AppraisalController::class, 'edit'])->name('appraisals.edit');
Route::put('/appraisals/{appraisal}', [AppraisalController::class, 'update'])->name('appraisals.update');

// Make employee a supervisor
Route::put('/admin/users/{employeeNumber}/make-supervisor', [Admin\UserController::class, 'makeSupervisor'])->name('admin.users.make-supervisor');

// User Management Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/search', [UserController::class, 'search'])->name('admin.users.search');
    Route::get('/users/inactive', [UserController::class, 'inactive'])->name('admin.users.inactive');
    Route::get('/users/export', [UserController::class, 'export'])->name('admin.users.export');
    Route::get('/users/{employeeNumber}', [UserController::class, 'show'])->name('admin.users.show');
    
    // Make Supervisor Route
    Route::put('/users/{employeeNumber}/make-supervisor', [UserController::class, 'makeSupervisor'])->name('admin.users.make-supervisor');
    
    // Other user management routes
    Route::post('/users/{employeeNumber}/mark-as-left', [UserController::class, 'markAsLeft'])->name('admin.users.mark-as-left');
    Route::put('/users/{employeeNumber}/reactivate', [UserController::class, 'reactivate'])->name('admin.users.reactivate');
});

// Add this route
Route::post('/appraisals/adjust-rating', [AppraisalQuarterlySummaryController::class, 'adjustRating'])
    ->name('appraisals.adjust-rating')
    ->middleware('auth');


    // ==============================================
// PIP MANAGEMENT ROUTES
// ==============================================
Route::middleware(['auth'])->group(function () {
    // View all PIPs (Supervisors see their team, Admins see all)
    Route::get('/pip-management', [App\Http\Controllers\AppraisalController::class, 'pipManagement'])
        ->name('pip.management');
    
    // View single PIP details
    Route::get('/pip/{appraisal}', [App\Http\Controllers\AppraisalController::class, 'showPipDetails'])
        ->name('pip.show');
    
    // Update PIP status
    Route::put('/pip/{appraisal}/update-status', [App\Http\Controllers\AppraisalController::class, 'updatePipStatus'])
        ->name('pip.update-status');
    
    // Export PIPs to Excel/CSV
    Route::get('/pip/export', [App\Http\Controllers\AppraisalController::class, 'exportPips'])
        ->name('pip.export');
});