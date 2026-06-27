<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\CmsPage;
class FaqController extends Controller
{
    public function index()
    {
        $page = CmsPage::where('slug', 'faq')->first();
        $fee  = number_format((float)\App\Models\Setting::get('assessment_fee', '235'), 0);
        return view('public.faq', compact('page', 'fee'));
    }
}
