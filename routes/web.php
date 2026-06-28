<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ServicesController;
use App\Http\Controllers\Public\TeamController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HcpReferralController;
use App\Http\Controllers\Public\AssessmentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicationsController;
use App\Http\Controllers\Admin\HcpReferralsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\ContactSubmissionsController;
use App\Http\Controllers\Admin\CmsPagesController;
use App\Http\Controllers\Admin\EmailLogsController;
use App\Http\Controllers\Admin\EmailTemplatesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeamMembersController;
use App\Http\Controllers\Admin\TestimonialsController;
use App\Http\Controllers\Admin\ServicesAdminController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\ReportsController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/service/{slug}', [ServicesController::class, 'show'])->name('service.show');
Route::get('/team', [TeamController::class, 'index'])->name('team');
Route::get('/team/{slug}', [TeamController::class, 'show'])->name('team.show');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/privacy-policy', fn() => view('public.policy', ['slug' => 'privacy-policy', 'title' => 'Privacy Policy']))->name('privacy-policy');
Route::get('/terms-of-use',   fn() => view('public.policy', ['slug' => 'terms-of-use',   'title' => 'Terms of Use']))->name('terms');
Route::get('/cookie-policy',  fn() => view('public.policy', ['slug' => 'cookie-policy',  'title' => 'Cookie Policy']))->name('cookies');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/hcp-referral-form', [HcpReferralController::class, 'index'])->name('hcp-referral');
Route::post('/hcp-referral-form', [HcpReferralController::class, 'submit'])->name('hcp-referral.submit');
Route::get('/hcp-referral-form/thank-you', [HcpReferralController::class, 'thanks'])->name('hcp-referral.thanks');
Route::get('/arrange-assessment', [AssessmentController::class, 'index'])->name('assessment.index');
Route::post('/arrange-assessment/checkout', [AssessmentController::class, 'createCheckout'])->name('assessment.checkout');
Route::get('/arrange-assessment/success', [AssessmentController::class, 'success'])->name('assessment.success');
// ORIGINAL: DAI feedback 26-06-26 — env('STRIPE_SECRET') → DB settings check
// if (app()->environment('local', 'testing') || !env('STRIPE_SECRET')) {
if (app()->environment('local', 'testing') || !\App\Models\Setting::get('stripe_secret_key_test')) {
    Route::get('/arrange-assessment/test-bypass', [AssessmentController::class, 'testBypass'])->name('assessment.test-bypass');
}
Route::get('/assessment/{token}', [AssessmentController::class, 'application'])->name('assessment.application');
Route::post('/assessment/{token}/step', [AssessmentController::class, 'saveStep'])->name('assessment.save-step');
Route::post('/assessment/{token}/submit', [AssessmentController::class, 'submit'])->name('assessment.submit');

// Admin auth
Route::get('/admin', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin protected routes
Route::middleware(\App\Http\Middleware\AdminAuth::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/applications', [ApplicationsController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationsController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/status', [ApplicationsController::class, 'updateStatus'])->name('applications.status');
    Route::get('/hcp-referrals', [HcpReferralsController::class, 'index'])->name('hcp-referrals.index');
    Route::get('/hcp-referrals/{hcpReferral}', [HcpReferralsController::class, 'show'])->name('hcp-referrals.show');
    Route::get('/hcp-referrals/{hcpReferral}/document', [HcpReferralsController::class, 'downloadDocument'])->name('hcp-referrals.document');
    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('/contact-submissions', [ContactSubmissionsController::class, 'index'])->name('contact-submissions.index');
    Route::get('/contact-submissions/{contactSubmission}', [ContactSubmissionsController::class, 'show'])->name('contact-submissions.show');
    Route::get('/cms-pages', [CmsPagesController::class, 'index'])->name('cms-pages.index');
    Route::get('/cms-pages/{cmsPage}/edit', [CmsPagesController::class, 'edit'])->name('cms-pages.edit');
    Route::post('/cms-pages/{cmsPage}', [CmsPagesController::class, 'update'])->name('cms-pages.update');
    Route::post('/cms-pages/{cmsPage}/video', [CmsPagesController::class, 'uploadVideo'])->name('cms-pages.upload-video');
    Route::delete('/cms-pages/{cmsPage}/video', [CmsPagesController::class, 'removeVideo'])->name('cms-pages.remove-video');
    Route::get('/services', [ServicesAdminController::class, 'index'])->name('services.index');
    Route::post('/services/{cmsPage}/image', [ServicesAdminController::class, 'uploadImage'])->name('services.upload-image');
    Route::delete('/services/{cmsPage}/image', [ServicesAdminController::class, 'removeImage'])->name('services.remove-image');
    Route::delete('/services/{cmsPage}', [ServicesAdminController::class, 'destroy'])->name('services.destroy');
    Route::get('/email-logs', [EmailLogsController::class, 'index'])->name('email-logs.index');
    Route::delete('/email-logs', [EmailLogsController::class, 'clear'])->name('email-logs.clear');
    Route::get('/email-templates', [EmailTemplatesController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/{emailTemplate}/edit', [EmailTemplatesController::class, 'edit'])->name('email-templates.edit');
    Route::post('/email-templates/{emailTemplate}', [EmailTemplatesController::class, 'update'])->name('email-templates.update');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [SettingsController::class, 'testEmail'])->name('settings.test-email');
    Route::get('/admin-users', [AdminUsersController::class, 'index'])->name('admin-users.index');
    Route::post('/admin-users', [AdminUsersController::class, 'store'])->name('admin-users.store');
    Route::put('/admin-users/{adminUser}', [AdminUsersController::class, 'update'])->name('admin-users.update');
    Route::delete('/admin-users/{adminUser}', [AdminUsersController::class, 'destroy'])->name('admin-users.destroy');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/team-members', [TeamMembersController::class, 'index'])->name('team-members.index');
    Route::get('/team-members/create', [TeamMembersController::class, 'create'])->name('team-members.create');
    Route::post('/team-members', [TeamMembersController::class, 'store'])->name('team-members.store');
    Route::get('/team-members/{teamMember}/edit', [TeamMembersController::class, 'edit'])->name('team-members.edit');
    Route::put('/team-members/{teamMember}', [TeamMembersController::class, 'update'])->name('team-members.update');
    Route::delete('/team-members/{teamMember}', [TeamMembersController::class, 'destroy'])->name('team-members.destroy');
    Route::get('/testimonials', [TestimonialsController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [TestimonialsController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialsController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialsController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialsController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialsController::class, 'destroy'])->name('testimonials.destroy');
});
