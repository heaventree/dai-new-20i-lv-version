<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AssessmentApplication;
use Illuminate\Http\Request;
class ApplicationsController extends Controller
{
    public function index(Request $request)
    {
        $query = AssessmentApplication::query();
        if ($request->search) {
            $q = $request->search;
            $query->where(function($qb) use ($q) {
                $qb->where('first_name','like',"%$q%")->orWhere('last_name','like',"%$q%")->orWhere('email','like',"%$q%")->orWhere('token','like',"%$q%");
            });
        }
        if ($request->status) $query->where('status', $request->status);
        $applications = $query->latest()->paginate(20)->withQueryString();
        return view('admin.applications.index', compact('applications'));
    }
    public function show(AssessmentApplication $application)
    {
        return view('admin.applications.show', compact('application'));
    }
    public function updateStatus(Request $request, AssessmentApplication $application)
    {
        $application->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
}
