<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;
class EmailLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::latest();
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('to_email', 'like', "%{$search}%")
                  ->orWhere('subject',  'like', "%{$search}%")
                  ->orWhere('template', 'like', "%{$search}%");
            });
        }
        $total  = EmailLog::count();
        $sent   = EmailLog::where('status', 'sent')->count();
        $failed = EmailLog::where('status', 'failed')->count();
        $logs   = $query->paginate(30);
        return view('admin.email-logs.index', compact('logs', 'total', 'sent', 'failed'));
    }

    public function clear()
    {
        EmailLog::truncate();
        return redirect()->route('admin.email-logs.index')->with('success', 'All email logs cleared.');
    }
}
