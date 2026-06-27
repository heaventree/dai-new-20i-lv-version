<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AssessmentApplication;
use App\Models\HcpReferral;
use App\Models\Payment;
use App\Models\ContactSubmission;
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_applications' => AssessmentApplication::count(),
            'pending_applications' => AssessmentApplication::where('status', 'pending')->count(),
            'completed_applications' => AssessmentApplication::where('status', 'completed')->count(),
            'hcp_referrals' => HcpReferral::count(),
            'total_revenue' => Payment::where('status', 'succeeded')->sum('amount'),
            'unread_contacts' => ContactSubmission::where('read', false)->count(),
        ];
        $recent_applications = AssessmentApplication::latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recent_applications'));
    }
}
