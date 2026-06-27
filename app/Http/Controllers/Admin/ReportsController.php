<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class ReportsController extends Controller
{
    public function index()
    {
        $ga4Id = Setting::get('ga4_id', '');
        $gtmId = Setting::get('gtm_id', '');
        return view('admin.reports.index', compact('ga4Id', 'gtmId'));
    }
}
