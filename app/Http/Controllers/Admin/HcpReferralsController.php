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
}
