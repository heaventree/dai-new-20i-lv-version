<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        $page = CmsPage::where('slug', 'about')->first();

        $members = TeamMember::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('public.about', compact('page', 'members'));
    }
}
