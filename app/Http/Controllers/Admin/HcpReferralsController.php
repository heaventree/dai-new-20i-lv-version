<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\HcpReferral;
use Illuminate\Http\Request;
class HcpReferralsController extends Controller
{
    public function index(Request $request)
    {
        $query = HcpReferral::query();
        if ($request->search) {
            $q = $request->search;
            $query->where(function($qb) use ($q) {
                $qb->where('hcp_name','like',"%$q%")->orWhere('patient_full_name','like',"%$q%")->orWhere('hcp_email','like',"%$q%")->orWhere('hcp_registration_no','like',"%$q%");
            });
        }
        $referrals = $query->latest()->paginate(20)->withQueryString();
        return view('admin.hcp-referrals.index', compact('referrals'));
    }
    public function show(HcpReferral $hcpReferral)
    {
        return view('admin.hcp-referrals.show', compact('hcpReferral'));
    }

    public function downloadDocument(HcpReferral $hcpReferral)
    {
        if (!$hcpReferral->document_path) {
            abort(404);
        }
        $path = storage_path('app/' . $hcpReferral->document_path);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->download($path, $hcpReferral->document_name ?: basename($path));
    }
}
